<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Build;

use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

use ReflectionClass;
use ReflectionException;
use ReflectionExtension;
use ReflectionMethod;
use function array_keys;
use function array_values;
use function dirname;
use function fopen;
use function implode;
use function is_dir;
use function json_encode;
use function ksort;
use function mkdir;
use function natsort;
use function sprintf;
use const DIRECTORY_SEPARATOR;
use const JSON_PRETTY_PRINT;

/**
 * Handler to build new JSON definition files for an extension already loaded in memory.
 *
 * @since Release 3.5.0
 * @author Laurent Laville
 */
final class BuildHandler implements CommandHandlerInterface
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(BuildCommand $command): void
    {
        $output = $command->getOutput();
        $extension = $command->getExtension();
        $phpMin = $command->getPhpMin();
        $extMin = $command->getExtMin();

        $re = new ReflectionExtension($extension);
        $classes = $re->getClassNames();
        natsort($classes);

        $extDir = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 4), 'data', 'reference', 'extension', $extension, '0']);
        if (!is_dir($extDir)) {
            mkdir($extDir, 0755, true);
        }

        if (is_array($classes)) {
            // CLASSES
            $filename = $extDir . DIRECTORY_SEPARATOR . 'classes.json';
            if ($output->isDebug()) {
                $output->writeln(sprintf('<info>%s</info>', $filename));
            } else {
                $output = new StreamOutput(fopen($filename, 'a'));
            }
            $this->buildClasses($extMin, $phpMin, $re, $output);

            // INTERFACES
            $filename = $extDir . DIRECTORY_SEPARATOR . 'interfaces.json';
            if ($output->isDebug()) {
                $output->writeln(sprintf('<info>%s</info>', $filename));
            } else {
                $output = new StreamOutput(fopen($filename, 'a'));
            }
            $this->buildInterfaces($extMin, $phpMin, $re, $output);

            // METHODS
            $filename = $extDir . DIRECTORY_SEPARATOR . 'methods.json';
            if ($output->isDebug()) {
                $output->writeln(sprintf('<info>%s</info>', $filename));
            } else {
                $output = new StreamOutput(fopen($filename, 'a'));
            }
            $this->buildMethods($extMin, $phpMin, $classes, $output);

            // CLASSES CONSTANTS
            $filename = $extDir . DIRECTORY_SEPARATOR . 'const.json';
            if ($output->isDebug()) {
                $output->writeln(sprintf('<info>%s</info>', $filename));
            } else {
                $output = new StreamOutput(fopen($filename, 'a'));
            }
            $this->buildClassConstants($extMin, $phpMin, $classes, $output);
        }

        // CONSTANTS
        $filename = $extDir . DIRECTORY_SEPARATOR . 'constants.json';
        if ($output->isDebug()) {
            $output->writeln(sprintf('<info>%s</info>', $filename));
        } else {
            $output = new StreamOutput(fopen($filename, 'a'));
        }
        $this->buildConstants($extMin, $phpMin, $re, $output);

        // FUNCTIONS
        $filename = $extDir . DIRECTORY_SEPARATOR . 'functions.json';
        if ($output->isDebug()) {
            $output->writeln(sprintf('<info>%s</info>', $filename));
        } else {
            $output = new StreamOutput(fopen($filename, 'a'));
        }
        $this->buildFunctions($extMin, $phpMin, $re, $output);
    }

    private function buildClasses(string $extMin, string $phpMin, ReflectionExtension $re, OutputInterface $output): void
    {
        $objects = [];

        foreach ($re->getClasses() as $className => $rc) {
            if ($rc->isInterface()) {
                continue;
            }

            $data = [
                'name' => $className,
                'ext_min' => $extMin,
                'php_min' => $phpMin,
            ];
            $objects[$className] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildInterfaces(string $extMin, string $phpMin, ReflectionExtension $re, OutputInterface $output): void
    {
        $objects = [];

        foreach ($re->getClasses() as $className => $rc) {
            if (!$rc->isInterface()) {
                continue;
            }

            $data = [
                'name' => $className,
                'ext_min' => $extMin,
                'php_min' => $phpMin,
            ];
            $objects[$className] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    /**
     * @param string[] $classes
     * @throws ReflectionException
     */
    private function buildMethods(string $extMin, string $phpMin, array $classes, OutputInterface $output): void
    {
        $methods = [];

        foreach ($classes as $className) {
            $rc = new ReflectionClass($className);

            $meth = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
            natsort($meth);

            foreach ($meth as $method) {
                $methodName = $method->getName();

                try {
                    $method->getPrototype();
                    // if there is one method prototype, don't check it
                    if ($output->isVerbose()) {
                        $output->writeln(
                            sprintf('<comment>%s</comment> is a prototype', $methodName)
                        );
                    }
                    continue;
                } catch (ReflectionException $e) {
                }

                $from = $method->getDeclaringClass()->getName();

                if ($from !== $className) {
                    if ($output->isVerbose()) {
                        $output->writeln(
                            sprintf('<comment>%s</comment> inherit from <comment>%s</comment>', $methodName, $from)
                        );
                    }
                    continue;
                }

                $data = [
                    'class_name' => $className,
                    'name' => $methodName,
                    'ext_min' => $extMin,
                    'php_min' => $phpMin,
                ];
                if ($method->isStatic()) {
                    $data['static'] = true;
                }
                $methods[] = $data;
            }
        }

        $output->writeln(
            json_encode($methods, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @param string[] $classes
     * @throws ReflectionException
     */
    private function buildClassConstants(string $extMin, string $phpMin, array $classes, OutputInterface $output): void
    {
        $constants = [];

        foreach ($classes as $className) {
            $rc = new ReflectionClass($className);

            $const = array_keys($rc->getConstants());
            natsort($const);

            foreach ($const as $constName) {
                $data = [
                    'class_name' => $className,
                    'name' => $constName,
                    'ext_min' => $extMin,
                    'php_min' => $phpMin,
                ];
                $constants[] = $data;
            }
        }

        $output->writeln(
            json_encode($constants, JSON_PRETTY_PRINT)
        );
    }

    private function buildConstants(string $extMin, string $phpMin, ReflectionExtension $re, OutputInterface $output): void
    {
        $objects = [];

        foreach ($re->getConstants() as $name => $value) {
            $data = [
                'name' => $name,
                'ext_min' => $extMin,
                'php_min' => $phpMin,
            ];
            $objects[$name] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildFunctions(string $extMin, string $phpMin, ReflectionExtension $re, OutputInterface $output): void
    {
        $objects = [];

        foreach ($re->getFunctions() as $name => $rf) {
            $data = [
                'name' => $name,
                'ext_min' => $extMin,
                'php_min' => $phpMin,
            ];
            $objects[$name] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }
}

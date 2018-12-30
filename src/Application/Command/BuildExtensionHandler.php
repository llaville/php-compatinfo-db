<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\DatabaseFactory;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;

class BuildExtensionHandler implements CommandHandlerInterface
{
    private $stmtClass;
    
    public function __invoke(BuildExtensionCommand $command) : void
    {
        $extension = $command->extension;
        $extMin    = $command->extMin;
        $phpMin    = $command->phpMin;
        $output    = $command->output;

        $pdo = DatabaseFactory::create('sqlite');

        $stmtExtension = $pdo->prepare(
            'SELECT e.id as "ext_name_fk", e.name as "name"' .
            ' FROM bartlett_compatinfo_extensions e' .
            ' WHERE e.name = :name COLLATE NOCASE'
        );

        $this->stmtClass = $pdo->prepare(
            'SELECT c.ext_min as "ext_min", c.php_min as "php_min"' .
            ' FROM bartlett_compatinfo_classes c' .
            ' WHERE c.name = :name COLLATE NOCASE'
        );

        $inputParameters = array(':name' => $extension);
        $stmtExtension->execute($inputParameters);
        $result = $stmtExtension->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // unknown extension
            $extId   = -1;
            $extName = $extension;
        } else {
            $extId   = $result['ext_name_fk'];
            $extName = $result['name'];
        }

        // EXTENSIONS
        $data = ['id' => $extId, 'name' => $extName];
        $output->writeln(
            sprintf(
                '<info>%s.extensions.json</info>',
                ucfirst(strtolower($extension))
            )
        );
        $output->writeln(
            json_encode($data, JSON_PRETTY_PRINT)
        );

        $re = new \ReflectionExtension($extension);
        $classes = $re->getClassNames();
        natsort($classes);

        if (is_array($classes)) {
            // CLASSES
            $output->writeln(
                sprintf(
                    '<info>%s.classes.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildClasses($extId, $extMin, $phpMin, $re, $output);

            // INTERFACES
            $output->writeln(
                sprintf(
                    '<info>%s.interfaces.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildInterfaces($extId, $extMin, $phpMin, $re, $output);

            // METHODS
            $output->writeln(
                sprintf(
                    '<info>%s.methods.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildMethods($extId, $extMin, $phpMin, $classes, $output);

            // CLASSES CONSTANTS
            $output->writeln(
                sprintf(
                    '<info>%s.const.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildClassConstants($extId, $extMin, $phpMin, $classes, $output);
        }

        // CONSTANTS
        $output->writeln(
            sprintf(
                '<info>%s.constants.json</info>',
                ucfirst(strtolower($extension))
            )
        );
        $this->buildConstants($extId, $extMin, $phpMin, $re, $output);

        // FUNCTIONS
        $output->writeln(
            sprintf(
                '<info>%s.functions.json</info>',
                ucfirst(strtolower($extension))
            )
        );
        $this->buildFunctions($extId, $extMin, $phpMin, $re, $output);
    }

    private function buildClasses($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getClasses() as $className => $rc) {
            if ($rc->isInterface()) {
                continue;
            }

            $data = [
                'ext_name_fk' => $extId,
                'name' => $className,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
            ];
            $objects[$className] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildInterfaces($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getClasses() as $className => $rc) {
            if (!$rc->isInterface()) {
                continue;
            }

            $data = [
                'ext_name_fk' => $extId,
                'name' => $className,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
            ];
            $objects[$className] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildMethods($extId, $extMinDefault, $phpMinDefault, array $classes, OutputInterface $output)
    {
        $methods = [];

        foreach ($classes as $className) {
            $rc = new \ReflectionClass($className);

            $inputParameters = array(':name' => $className);
            $this->stmtClass->execute($inputParameters);
            $result = $this->stmtClass->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // pre-set ext and php MIN versions to better values than user custom (console inputs)
                $extMin = $result['ext_min'];
                $phpMin = $result['php_min'];
            } else {
                $extMin = $extMinDefault;
                $phpMin = $phpMinDefault;
            }

            $meth = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);
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
                } catch (\ReflectionException $e) {
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

                $static = $method->isStatic() ? 'true' : 'false';

                $data = [
                    'ext_name_fk' => $extId,
                    'class_name' => $className,
                    'name' => $methodName,
                    'static' => $static,
                    'ext_min' => $extMin,
                    'ext_max' => '',
                    'php_min' => $phpMin,
                    'php_max' => '',
                ];
                $methods[] = $data;
            }
        }

        $output->writeln(
            json_encode($methods, JSON_PRETTY_PRINT)
        );
    }

    private function buildClassConstants($extId, $extMinDefault, $phpMinDefault, array $classes, OutputInterface $output)
    {
        $constants = [];

        foreach ($classes as $className) {
            $rc = new \ReflectionClass($className);

            $inputParameters = array(':name' => $className);
            $this->stmtClass->execute($inputParameters);
            $result = $this->stmtClass->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // pre-set ext and php MIN versions to better values than user custom (console inputs)
                $extMin = $result['ext_min'];
                $phpMin = $result['php_min'];
            } else {
                $extMin = $extMinDefault;
                $phpMin = $phpMinDefault;
            }

            $const = array_keys($rc->getConstants());
            natsort($const);

            foreach ($const as $constName) {
                $data = [
                    'ext_name_fk' => $extId,
                    'class_name' => $className,
                    'name' => $constName,
                    'ext_min' => $extMin,
                    'ext_max' => '',
                    'php_min' => $phpMin,
                    'php_max' => '',
                ];
                $constants[] = $data;
            }
        }

        $output->writeln(
            json_encode($constants, JSON_PRETTY_PRINT)
        );
    }

    private function buildConstants($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getConstants() as $name => $value) {

            $data = [
                'ext_name_fk' => $extId,
                'name' => $name,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
                'php_excludes' => '',
            ];
            $objects[$name] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildFunctions($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getFunctions() as $name => $rf) {

            $data = [
                'ext_name_fk' => $extId,
                'name' => $name,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
                'parameters' => '',
                'php_excludes' => '',
            ];
            $objects[$name] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }
}

<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command\Debug;

use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;

use Symfony\Bundle\FrameworkBundle\Command\ContainerDebugCommand as ContainerDebugCommandSymfonyFrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

use function count;
use function ltrim;
use function str_replace;
use function str_starts_with;
use function stripos;

/**
 * Display current services for an application.
 *
 * @since Release 4.6.0
 * @author Laurent Laville
 */
final class ContainerDebugCommand extends ContainerDebugCommandSymfonyFrameworkBundle
{
    public const NAME = 'debug:container';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName(self::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $errorIo = $io->getErrorStyle();

        $this->validateInput($input);

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        $kernel = $app->getKernel();

        $object = $kernel->getContainerBuilder();

        if ($input->getOption('env-vars')) {
            $options = ['env-vars' => true];
        } elseif ($envVar = $input->getOption('env-var')) {
            $options = ['env-vars' => true, 'name' => $envVar];
        } elseif ($input->getOption('types')) {
            $options = [];
            $options['filter'] = [$this, 'filterToServiceTypes'];
        } elseif ($input->getOption('parameters')) {
            $parameters = [];
            foreach ($object->getParameterBag()->all() as $k => $v) {
                $parameters[$k] = $object->resolveEnvPlaceholders($v);
            }
            $object = new ParameterBag($parameters);
            $options = [];
        } elseif ($parameter = $input->getOption('parameter')) {
            $options = ['parameter' => $parameter];
        } elseif ($input->getOption('tags')) {
            $options = ['group_by' => 'tags'];
        } elseif ($tag = $input->getOption('tag')) {
            $options = ['tag' => $tag];
        } elseif ($name = $input->getArgument('name')) {
            $name = $this->findProperServiceName($input, $errorIo, $object, $name, $input->getOption('show-hidden'));
            $options = ['id' => $name];
        } elseif ($input->getOption('deprecations')) {
            $options = ['deprecations' => true];
        } else {
            $options = [];
        }

        $helper = new DescriptorHelper();
        $options['format'] = $input->getOption('format');
        $options['show_arguments'] = $input->getOption('show-arguments');
        $options['show_hidden'] = $input->getOption('show-hidden');
        $options['raw_text'] = $input->getOption('raw');
        $options['output'] = $io;
        $options['is_debug'] = $kernel->isDebug();

        try {
            $helper->describe($io, $object, $options);

            if (isset($options['id']) && isset($kernel->getContainer()->getRemovedIds()[$options['id']])) {
                $errorIo->note(sprintf('The "%s" service or alias has been removed or inlined when the container was compiled.', $options['id']));
            }
        } catch (ServiceNotFoundException $e) {
            if ('' !== $e->getId() && '@' === $e->getId()[0]) {
                throw new ServiceNotFoundException($e->getId(), $e->getSourceId(), null, [substr($e->getId(), 1)]);
            }

            throw $e;
        }

        if (!$input->getArgument('name') && !$input->getOption('tag') && !$input->getOption('parameter') && !$input->getOption('env-vars') && !$input->getOption('env-var') && $input->isInteractive()) {
            if ($input->getOption('tags')) {
                $errorIo->comment('To search for a specific tag, re-run this command with a search term. (e.g. <comment>debug:container --tag=form.type</comment>)');
            } elseif ($input->getOption('parameters')) {
                $errorIo->comment('To search for a specific parameter, re-run this command with a search term. (e.g. <comment>debug:container --parameter=kernel.debug</comment>)');
            } elseif (!$input->getOption('deprecations')) {
                $errorIo->comment('To search for a specific service, re-run this command with a search term. (e.g. <comment>debug:container log</comment>)');
            }
        }

        return 0;   // SUCCESS
    }

    private function findProperServiceName(InputInterface $input, SymfonyStyle $io, ContainerBuilder $builder, string $name, bool $showHidden): string
    {
        $name = ltrim($name, '\\');

        if ($builder->has($name) || !$input->isInteractive()) {
            return $name;
        }

        $matchingServices = $this->findServiceIdsContaining($builder, $name, $showHidden);
        if (empty($matchingServices)) {
            throw new InvalidArgumentException(sprintf('No services found that match "%s".', $name));
        }

        if (1 === count($matchingServices)) {
            return $matchingServices[0];
        }

        return $io->choice('Select one of the following services to display its information', $matchingServices);
    }

    /**
     * @return array<int, string>
     */
    private function findServiceIdsContaining(ContainerBuilder $builder, string $name, bool $showHidden): array
    {
        $serviceIds = $builder->getServiceIds();
        $foundServiceIds = $foundServiceIdsIgnoringBackslashes = [];
        foreach ($serviceIds as $serviceId) {
            if (!$showHidden && str_starts_with($serviceId, '.')) {
                continue;
            }
            if (false !== stripos(str_replace('\\', '', $serviceId), $name)) {
                $foundServiceIdsIgnoringBackslashes[] = $serviceId;
            }
            if ('' === $name || false !== stripos($serviceId, $name)) {
                $foundServiceIds[] = $serviceId;
            }
        }

        return $foundServiceIds ?: $foundServiceIdsIgnoringBackslashes;
    }
}

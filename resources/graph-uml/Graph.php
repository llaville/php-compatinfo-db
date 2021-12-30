<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\GraphUml\Generator\GraphVizGenerator;
use Bartlett\UmlWriter\Generator\GeneratorFactory;
use Bartlett\UmlWriter\Service\ClassDiagramRenderer;

use Symfony\Component\Finder\Finder;

/**
 * @since Release 3.17.0
 * @author Laurent Laville
 */
final class Graph
{
    private Finder $finder;
    private ?string $folder;
    private string $basename;

    public function __construct(Finder $finder, string $basename, ?string $folder = null)
    {
        $this->finder = $finder;
        $this->folder = $folder;
        $this->basename = $basename;
    }

    public function __invoke(): void
    {
        $generatorFactory = new GeneratorFactory('graphviz');
        /** @var GraphVizGenerator $generator */
        $generator = $generatorFactory->getGenerator();

        $color1 = 'burlywood3';     // Application layer
        $color2 = 'cadetblue3';     // Domain layer
        $color3 = 'chartreuse3';    // Infrastructure layer
        $color4 = 'chocolate3';     // Presentation layer

        $renderer = new ClassDiagramRenderer();
        $options = [
            'show_private' => false,
            'show_protected' => false,
            'node.fillcolor' => '#FEFECE',
            'node.style' => 'filled',
            'graph.rankdir' => 'LR',
            'cluster.Bartlett\\CompatInfoDb\\Application\\Command.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Build.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Release.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Event.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Event\\Dispatcher.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Event\\Subscriber.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Query.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\Init.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\ListRef.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\Show.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\Diagnose.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\Doctor.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Application\\Service.graph.bgcolor' => $color1,
            'cluster.Bartlett\\CompatInfoDb\\Domain\\Factory.graph.bgcolor' => $color2,
            'cluster.Bartlett\\CompatInfoDb\\Domain\\Repository.graph.bgcolor' => $color2,
            'cluster.Bartlett\\CompatInfoDb\\Domain\\ValueObject.graph.bgcolor' => $color2,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Bus.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Bus\\Command.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Bus\\Query.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Framework\\Symfony.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Framework\\Symfony\\DependencyInjection.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Persistence\\Doctrine\\Entity.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Persistence\\Doctrine\\Hydrator.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Infrastructure\\Persistence\\Doctrine\\Repository.graph.bgcolor' => $color3,
            'cluster.Bartlett\\CompatInfoDb\\Presentation\\Console.graph.bgcolor' => $color4,
            'cluster.Bartlett\\CompatInfoDb\\Presentation\\Console\\Command.graph.bgcolor' => $color4,
            'cluster.Bartlett\\CompatInfoDb\\Presentation\\Console\\Input.graph.bgcolor' => $color4,
            'cluster.Bartlett\\CompatInfoDb\\Presentation\\Console\\Output.graph.bgcolor' => $color4,
        ];

        try {
            $renderer($this->finder, $generator, $options);
        } catch (ReflectionException $exception) {
            echo "Unable to generate graph. Following error has occurred : " . $exception->getMessage() . PHP_EOL;
            return;
        }

        // default format is PNG, change it to SVG
        $generator->setFormat('svg');

        if (isset($this->folder)) {
            $cmdFormat = '%E -T%F %t -o '
                . rtrim($this->folder, DIRECTORY_SEPARATOR) . '/' . $this->basename . '.graphviz.%F';
        } else {
            $cmdFormat = '';
        }
        $graph = $renderer->getGraph();
        $target = $generator->createImageFile($graph, $cmdFormat);
        echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
    }

    /**
     * @param string[] $paths
     */
    public static function from(string $dataSource, array $paths, string $basename, ?string $target)
    {
        foreach ($paths as $path) {
            $finder = new Finder();
            $finder->in($dataSource . '/' . $path)->name('*.php');
            $self = new self($finder, $basename . '_' . strtolower($path), $target);
            $self();
        }
    }
}

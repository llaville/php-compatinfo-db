<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @since Release 6.1.0
 * @author Laurent Laville
 */

return [
    // @link https://graphviz.gitlab.io/docs/attrs/rankdir/
    'graph.rankdir' => 'LR',
    // @link https://plantuml.com/en/color
    'cluster.Bartlett\\CompatInfoDb\\Application\\Query.graph.bgcolor' => 'BurlyWood',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\Diagnose.graph.bgcolor' => 'Bisque',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\Show.graph.bgcolor' => 'Bisque',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Query\\ListRef.graph.bgcolor' => 'Bisque',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Command.graph.bgcolor' => 'LightSkyBlue',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Build.graph.bgcolor' => 'LightBlue',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Create.graph.bgcolor' => 'LightBlue',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Init.graph.bgcolor' => 'LightBlue',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Polyfill.graph.bgcolor' => 'LightBlue',
    'cluster.Bartlett\\CompatInfoDb\\Application\\Command\\Release.graph.bgcolor' => 'LightBlue',
];

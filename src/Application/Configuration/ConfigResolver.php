<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Configuration;

use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
final class ConfigResolver
{
    private InputInterface $input;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        $configFiles = [];

        $configFile = $this->getOptionValue($this->input);
        if ($configFile !== null) {
            $configFiles[] = $configFile;
        }

        return $configFiles;
    }

    private function getOptionValue(InputInterface $input): ?string
    {
        foreach (['--config', '-c'] as $optionName) {
            if ($input->hasParameterOption($optionName, true)) {
                return $input->getParameterOption($optionName, null, true);
            }
        }

        return null;
    }
}

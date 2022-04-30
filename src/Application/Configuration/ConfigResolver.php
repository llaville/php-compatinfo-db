<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Configuration;

use Symfony\Component\Console\Input\InputInterface;

use function dirname;
use function implode;
use const DIRECTORY_SEPARATOR;

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
        $configFiles = [
            'common.php',
            implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 3), 'config', 'packages', 'messenger.php']),
        ];

        $configFile = $this->getOptionValue($this->input);
        if ($configFile === null) {
            // default configuration file, if none specified
            $configFiles[] = 'default.php';
        } else {
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

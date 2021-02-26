<?php declare(strict_types=1);

/**
 * Contract for output decorator.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @since Release 3.3.0
 */
interface StyleInterface extends OutputInterface
{
    /**
     * @param mixed $lines
     * @param string $format
     */
    public function columns($lines, string $format): void;

    /**
     * @param string $message
     * @return mixed
     */
    public function title(string $message);

    /**
     * @param string $message
     * @return mixed
     */
    public function section(string $message);

    /**
     * @param string[] $elements
     * @param array<string, string> $attributes
     * @return mixed
     */
    public function listing(array $elements, array $attributes);

    /**
     * @param string|string[] $message
     * @param string|null $format
     * @return mixed
     */
    public function text($message, ?string $format = null);

    /**
     * @param string|string[] $message
     * @return mixed
     */
    public function success($message);

    /**
     * @param string|string[] $message
     * @return mixed
     */
    public function error($message);

    /**
     * @param array<string> $headers
     * @param array<string>|array<TableSeparator> $rows
     * @param string $style default to 'compact' rather than 'symfony-style-guide'
     * @return void
     */
    public function table(array $headers, array $rows, string $style = 'compact');
}

<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Contract for output decorator.
 *
 * @since Release 3.3.0
 * @author Laurent Laville
 */
interface StyleInterface extends OutputInterface
{
    /**
     * @param string|string[] $lines
     */
    public function columns(string|array $lines, string $format): void;

    /**
     * @return mixed
     */
    public function title(string $message);

    /**
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
    public function success(string|array $message);

    /**
     * @param string|string[] $message
     * @return mixed
     */
    public function error(string|array $message);

    /**
     * @param array<string> $headers
     * @param array<string>|array<TableSeparator> $rows
     * @param string $style default to 'compact' rather than 'symfony-style-guide'
     * @return void
     */
    public function table(array $headers, array $rows, string $style = 'compact');
}

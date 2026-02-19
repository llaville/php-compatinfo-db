<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

use function array_map;
use function array_values;
use function func_get_arg;
use function func_num_args;
use function is_array;
use function min;
use function sprintf;
use function vsprintf;
use function wordwrap;
use const DIRECTORY_SEPARATOR;

/**
 * Output decorator helpers.
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
final class Style extends SymfonyStyle implements StyleInterface
{
    private int $lineLength;

    /**
     * Style constructor.
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);

        $width = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int) (DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
    }

    /**
     * Formats informational text.
     *
     * @param string|array<string> $message
     */
    public function text(string|array $message): void
    {
        if (func_num_args() === 1) {
            parent::text($message);
            return;
        }
        $format = func_get_arg(1);

        $messages = is_array($message) ? array_values($message) : [$message];
        foreach ($messages as $message) {
            $message = wordwrap($message, $this->lineLength);
            $message = sprintf('<%s>%s</>', $format, $message);
            $this->writeln($message);
        }
    }

    /**
     * Formats a list.
     *
     * @param array<string> $elements
     * @param array<string, string> $attributes
     */
    public function listing(array $elements, array $attributes = ['type' => '*', 'style' => '', 'indent' => '  ']): void
    {
        $type = $attributes['type'] ?? '*';
        $style = $attributes['style'] ?? '';
        $indent = $attributes['indent'] ?? '  ';

        $elements = array_map(function ($element) use ($type, $style, $indent) {
            if (empty($style)) {
                return sprintf('%s %s %s', $indent, $type, $element);
            }
            return sprintf('%s <%s>%s %s</>', $indent, $style, $type, $element);
        }, $elements);

        $this->writeln($elements);
        $this->newLine();
    }

    public function columns(mixed $lines, string $format): void
    {
        if (!is_array($lines)) {
            $lines = [$lines];
        }

        foreach ($lines as $args) {
            parent::text(vsprintf($format, [$args]));
        }
    }

    /**
     * Formats a table.
     *
     * @param array<string> $headers
     * @param list<list<string>|TableSeparator> $rows
     */
    public function table(array $headers, array $rows, string $style = 'compact'): void
    {
        $style = clone Table::getStyleDefinition($style);
        $style->setCellHeaderFormat('<info>%s</info>');

        $table = new Table($this);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setStyle($style);

        $table->render();
        $this->newLine();
    }
}

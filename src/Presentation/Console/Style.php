<?php declare(strict_types=1);

/**
 * Output decorator helpers.
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

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

use function array_map;
use function array_values;
use function is_array;
use function min;
use function sprintf;
use function vsprintf;
use function wordwrap;
use const DIRECTORY_SEPARATOR;

/**
 * @since 3.0.0
 */
final class Style extends SymfonyStyle implements StyleInterface
{
    /** @var int */
    private $lineLength;

    /**
     * Style constructor.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);

        $width = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int) (DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
    }

    /**
     * @param string|string[] $message
     * @param string|null $format
     * @return mixed
     */
    public function text($message, ?string $format = null)
    {
        if (null === $format) {
            parent::text($message);
            return;
        }
        $messages = is_array($message) ? array_values($message) : [$message];
        foreach ($messages as $message) {
            $message = wordwrap($message, $this->lineLength);
            $message = sprintf('<%s>%s</>', $format, $message);
            $this->writeln($message);
        }
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function columns($lines, string $format): void
    {
        if (!is_array($lines)) {
            $lines = [$lines];
        }

        foreach ($lines as $args) {
            parent::text(vsprintf($format, [$args]));
        }
    }

    /**
     * {@inheritDoc}
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

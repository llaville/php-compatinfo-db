<?php declare(strict_types=1);

/**
 * Value Object of console db:list command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\ListRef;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

/**
 * @since Release 2.0.0RC1
 */
final class ListQuery implements QueryInterface
{
    /** @var bool  */
    private $all;
    /** @var bool  */
    private $installed;
    /** @var string  */
    private $appVersion;
    /** @var string[] */
    private $filters;

    /**
     * ListQuery constructor.
     *
     * @param bool $all
     * @param bool $installed
     * @param string $appVersion
     * @param string[] $filters
     */
    public function __construct(
        bool $all,
        bool $installed,
        string $appVersion,
        array $filters = []
    ) {
        $this->all = $all;
        $this->installed = $installed;
        $this->appVersion = $appVersion;
        $this->filters = $filters;
    }

    public function isAll(): bool
    {
        return $this->all;
    }

    public function isInstalled(): bool
    {
        return $this->installed;
    }

    /**
     * @return string
     */
    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}

<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\ListRef;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

/**
 * Value Object of console db:list command.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ListQuery implements QueryInterface
{
    private bool $all;
    private bool $installed;
    private string $appVersion;
    /** @var string[] */
    private array $filters;

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

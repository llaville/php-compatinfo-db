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
    /**
     * ListQuery constructor.
     *
     * @param string $appVersion
     * @param string[] $filters
     */
    public function __construct(
        private readonly string $appVersion,
        private readonly array $filters = []
    ) {
    }

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

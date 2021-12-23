<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Show;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

/**
 * Handler to show details of a reference in the database.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ShowHandler implements QueryHandlerInterface
{
    private ExtensionFactory $factory;

    /**
     * ShowHandler constructor.
     *
     * @param ExtensionFactory $extensionFactory
     */
    public function __construct(ExtensionFactory $extensionFactory)
    {
        $this->factory = $extensionFactory;
    }

    /**
     * @param ShowQuery $query
     * @return Extension|null
     */
    public function __invoke(ShowQuery $query): ?Extension
    {
        return $this->factory->create($query->getExtension());
    }
}

<?php declare(strict_types=1);

/**
 * Handler to show details of a reference in the database.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Show;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

/**
 * @since Release 2.0.0RC1
 */
final class ShowHandler implements QueryHandlerInterface
{
    /** @var ExtensionFactory */
    private $factory;

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

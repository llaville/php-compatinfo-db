<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Database;

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ConstantRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\ArrayInput;

use function version_compare;
use const PHP_VERSION;

/**
 * Database functional tests for PHP_CompatInfo_Db.
 *
 * @since Release 6.4.1
 * @author Laurent Laville
 */
class ConstantRepositoryTest extends \PHPUnit\Framework\TestCase
{
    private EntityManagerInterface $entityManager;
    private ConstantRepository $constantRepository;

    protected function setUp(): void
    {
        $kernel = new ConsoleKernel($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'tests', false);

        $container = $kernel->createFromInput(new ArrayInput([]));

        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->constantRepository = new ConstantRepository($this->entityManager);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }

    public function testGetConstantByName(): void
    {
        $constant = $this->constantRepository->getConstantByName('T_BAD_CHARACTER', null);

        if (version_compare(PHP_VERSION, '7.4.0', 'ge')) {
            $expectedVersion = '7.4.0beta1';
        } else {
            $expectedVersion = '4.2.0';
        }
        $this->assertSame($expectedVersion, $constant->getPhpMin());
    }
}

<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Database;

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ClassRepository;

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
class ClassRepositoryTest extends \PHPUnit\Framework\TestCase
{
    private EntityManagerInterface $entityManager;
    private ClassRepository $classRepository;

    protected function setUp(): void
    {
        $kernel = new ConsoleKernel($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'tests', false);

        $container = $kernel->createFromInput(new ArrayInput([]));

        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->classRepository = new ClassRepository($this->entityManager);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }

    public function testGetClassByName(): void
    {
        $class = $this->classRepository->getClassByName('AMQPException', false);

        if (version_compare(PHP_VERSION, '7.4.0', 'ge')) {
            $expectedVersion = '7.4.0';
        } else {
            $expectedVersion = '5.2.0';
        }
        $this->assertSame($expectedVersion, $class->getPhpMin());
    }
}

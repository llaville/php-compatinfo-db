<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Create;

use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;

use Doctrine\ORM\Tools\SchemaTool;

use RuntimeException;
use Throwable;
use function sprintf;
use function str_contains;

/**
 * Handler to build Database schema.
 *
 * @since Release 3.19.0
 * @author Laurent Laville
 */
final class CreateHandler implements CommandHandlerInterface
{
    private const RETURN_CODE_DATABASE_ALREADY_EXISTS = 110;
    private const RETURN_CODE_SCHEMA_TOOL_FAILURE = 500;

    public function __invoke(CreateCommand $command): void
    {
        $entityManager = $command->getEntityManager();

        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        if (empty($metadata)) {
            throw new RuntimeException('No Metadata Classes found to create Database.');
        }

        $schemaTool = new SchemaTool($entityManager);

        $schema = $schemaTool->getSchemaFromMetadata($metadata);

        $conn = $entityManager->getConnection();
        $createSchemaSql = $schema->toSql($conn->getDatabasePlatform());
        foreach ($createSchemaSql as $sql) {
            try {
                $conn->executeQuery($sql);
            } catch (Throwable $e) {
                if (str_contains($e->getMessage(), 'already exists')) {
                    $error = 'Database already exists. Use instead `db:init --force` command to reset contents.';
                    $code = self::RETURN_CODE_DATABASE_ALREADY_EXISTS;
                } else {
                    $error = sprintf('Schema-Tool failed with Error "%s" while executing DDL: %s', $e->getMessage(), $sql);
                    $code = self::RETURN_CODE_SCHEMA_TOOL_FAILURE;
                }
                throw new RuntimeException($error, $code);
            }
        }
    }
}

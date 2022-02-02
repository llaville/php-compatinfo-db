<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure;

use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseQuery;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;

use Doctrine\DBAL\Connection;

use Symfony\Requirements\RequirementCollection;

use Exception;
use function extension_loaded;
use function get_cfg_var;
use function is_file;
use function phpversion;
use function sprintf;
use function version_compare;

/**
 * Checks requirements for running CompatInfoDB.
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
class ProjectRequirements extends RequirementCollection implements RequirementsInterface
{
    public const REQUIRED_PHP_VERSION = '7.4.0';

    private string $helpStatus;

    /**
     * ProjectRequirements constructor.
     */
    public function __construct(DiagnoseQuery $query)
    {
        $installedPhpVersion = phpversion();
        $requiredPhpVersion = self::REQUIRED_PHP_VERSION;

        $this->addRequirement(
            version_compare($installedPhpVersion, $requiredPhpVersion, 'ge'),
            sprintf('PHP version must be at least %s', $requiredPhpVersion),
            sprintf(
                'You are running PHP version "<strong>%s</strong>",' .
                ' but CompatInfoDB needs at least PHP "<strong>%s</strong>" to run.',
                $installedPhpVersion,
                $requiredPhpVersion
            ),
            sprintf('Install PHP %s or newer (installed version is %s)', $requiredPhpVersion, $installedPhpVersion)
        );

        $conn = $query->getDatabaseConnection();
        $dbParams = $conn->getParams();

        $this->addRequirement(
            extension_loaded($dbParams['driver']),
            sprintf('%s extension must be available', $dbParams['driver']),
            sprintf('Install the <strong>%s</strong> extension', $dbParams['driver'])
        );

        if (in_array($dbParams['driver'], ['sqlite', 'sqlite3', 'pdo_sqlite'])) {
            $this->addRequirement(
                $this->checkDbFile($dbParams['path']),
                sprintf('Check if source "%s" can be reached', $dbParams['path']),
                $this->helpStatus
            );
        } else {
            $this->addRequirement(
                $this->checkDoctrineConnection($conn),
                sprintf('Check if DSN "%s" can be reached', $dbParams['url']),
                $this->helpStatus
            );
        }

        $tablesExists = $this->checkDoctrineListTables($conn);
        $this->addRequirement(
            $tablesExists,
            'Check if tables exists in database',
            $this->helpStatus
        );

        $this->addRequirement(
            $this->checkPlatformAvailable($conn, $tablesExists),
            'Check if database contains at least one distribution platform',
            $this->helpStatus
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpIniPath()
    {
        return get_cfg_var('cfg_file_path');
    }

    private function checkDbFile(string $path): bool
    {
        if (is_file($path)) {
            $this->helpStatus = sprintf('DB file %s seems good.', $path);
            return true;
        }
        $this->helpStatus = sprintf('DB file %s is not a regular file or have wrong permissions.', $path);
        return false;
    }

    private function checkDoctrineConnection(Connection $connection): bool
    {
        try {
            $connection->executeQuery($connection->getDatabasePlatform()->getDummySelectSQL());
            $this->helpStatus = 'Connection to database server was successful.';
            return true;
        } catch (Exception $e) {
            $this->helpStatus = 'Could not talk to database server.';
            return false;
        }
    }

    private function checkDoctrineListTables(Connection $connection): bool
    {
        try {
            $tables = $connection->executeQuery($connection->getDatabasePlatform()->getListTablesSQL())
                ->fetchFirstColumn()
            ;
            if (empty($tables)) {
                throw new Exception();
            }
            $this->helpStatus = 'Schema was already proceeded.';
            return true;
        } catch (Exception $e) {
            $this->helpStatus = 'Create the schema with "db:create" command.';
            return false;
        }
    }

    private function checkPlatformAvailable(Connection $connection, bool $tablesExists): bool
    {
        try {
            if (!$tablesExists) {
                throw new Exception();
            }

            $stmt = $connection->prepare('select id from platforms where description = :description limit 1');
            $var = DistributionRepository::DISTRIBUTION_DESC;
            $stmt->bindParam('description', $var);

            $platforms = $stmt->executeQuery()
                ->fetchFirstColumn()
            ;
            if (empty($platforms)) {
                throw new Exception();
            }
            return true;
        } catch (Exception $e) {
            $this->helpStatus = 'At least one distribution platform should exist. None available. Run "db:init" command to build one.';
            return false;
        }
    }
}

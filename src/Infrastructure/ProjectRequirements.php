<?php declare(strict_types=1);

/**
 * Checks requirements for running CompatInfoDB.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Infrastructure;

use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseQuery;

use Doctrine\DBAL\Connection;

use Symfony\Requirements\RequirementCollection;

use Exception;
use function extension_loaded;
use function get_cfg_var;
use function is_file;
use function is_readable;
use function phpversion;
use function sprintf;
use function version_compare;

/**
 * @since 3.0.0
 */
class ProjectRequirements extends RequirementCollection implements RequirementsInterface
{
    const REQUIRED_PHP_VERSION = '7.4.0';

    /** @var string */
    private $helpStatus;

    /**
     * ProjectRequirements constructor.
     *
     * @param DiagnoseQuery $query
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

        $this->addRequirement(
            $this->checkDoctrineListTables($conn),
            'Check if tables exists in database',
            $this->helpStatus
        );

        $this->addRequirement(
            $this->checkPlatformAvailable($conn),
            sprintf('Check if platforms are available in database'),
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

    /**
     * @param string $path
     * @return bool
     */
    private function checkDbFile(string $path): bool
    {
        if (is_file($path) && is_readable($path)) {
            $this->helpStatus = sprintf('DB file %s seems good', $path);
            return true;
        }
        $this->helpStatus = sprintf('Something is wrong with DB file %s', $path);
        return false;
    }

    /**
     * @param Connection $connection
     * @return bool
     */
    private function checkDoctrineConnection(Connection $connection): bool
    {
        try {
            $connection->executeQuery($connection->getDatabasePlatform()->getDummySelectSQL());
            $this->helpStatus = 'Connection to database server was successful';
            return true;
        } catch (Exception $e) {
            $this->helpStatus = 'Could not talk to database server';
            return false;
        }
    }

    /**
     * @param Connection $connection
     * @return bool
     */
    private function checkDoctrineListTables(Connection $connection): bool
    {
        try {
            $tables = $connection->executeQuery($connection->getDatabasePlatform()->getListTablesSQL())
                ->fetchFirstColumn()
            ;
            if (empty($tables)) {
                throw new Exception('');
            }
            $this->helpStatus = 'Schema was already proceeded';
            return true;
        } catch (Exception $e) {
            $this->helpStatus = 'Create the schema with "vendor/bin/doctrine orm:schema-tool:create" command';
            return false;
        }
    }

    /**
     * @param Connection $connection
     * @return bool
     */
    private function checkPlatformAvailable(Connection $connection): bool
    {
        try {
            $platforms = $connection->executeQuery('select id from platforms limit 1')
                ->fetchFirstColumn()
            ;
            if (empty($platforms)) {
                throw new Exception('');
            }
            return true;
        } catch (Exception $e) {
            $this->helpStatus = 'At least one platform should exists. None available. Please run "db:init" command';
            return false;
        }
    }
}

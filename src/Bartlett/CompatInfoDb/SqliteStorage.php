<?php
/**
 * Reference DB Storage.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  GIT: $Id$
 * @link     http://php5.laurent-laville.org/compatinfo/
 */

namespace Bartlett\CompatInfoDb;

use Bartlett\CompatInfoDb\Environment;

use PDO;

/**
 * SQLite Reference Database.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: @package_version@
 * @link     http://php5.laurent-laville.org/compatinfo/
 * @since    Class available since Release 4.0.0-alpha2 of PHP_CompatInfo
 * @since    Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class SqliteStorage
{
    private $name;
    private $initialized = false;
    private $stmtReleases;
    private $stmtIniEntries;
    private $stmtClasses;
    private $stmtInterfaces;
    private $stmtClassMethods;
    private $stmtClassConstants;
    private $stmtFunctions;
    private $stmtConstants;
    private $stmtExtensions;

    /**
     * Creates a new storage corresponding to an extension in the database
     *
     * @param string $name Name of extension
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->initialize();
    }

    /**
     * Gets a list of informations about an extension present in storage
     *
     * @param string $meta   Identify the meta information to retrieve
     * @param bool   $static For class methods only
     *
     * @return array of meta informations
     */
    public function getMetaData($meta, $static = false)
    {
        $stmt = 'stmt' . ucfirst($meta);

        if (empty($this->name)) {
            $this->$stmt->execute();
        } else {
            $this->$stmt->execute(array(':name' => $this->name));
        }
        $rows = $this->$stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            if (!array_key_exists('php.excludes', $row)) {
                continue;
            }
            if (!empty($row['php.excludes'])) {
                $row['php.excludes'] = explode(',', $row['php.excludes']);
                $row['php.excludes'] = array_map('trim', $row['php.excludes']);
            } else {
                $row['php.excludes'] = array();
            }
        }

        $result = array();

        if ('classMethods' == $meta) {
            foreach ($rows as &$row) {
                if ($static && $row['static'] != 1) {
                    continue;
                }
                $className = $row['class_name'];
                $name = $row['name'];
                unset($row['name'], $row['class_name'], $row['static']);
                $result[$className][$name] = $row;
            }
        } elseif ('classConstants' == $meta) {
            foreach ($rows as &$row) {
                $className = $row['class_name'];
                $name = $row['name'];
                unset($row['name'], $row['class_name']);
                $result[$className][$name] = $row;
            }
        } elseif ('releases' == $meta) {
            foreach ($rows as &$row) {
                $name = $row['ext.min'];
                $result[$name] = $row;
            }
        } elseif ('extensions' == $meta) {
            $result = $rows;
        } else {
            foreach ($rows as &$row) {
                $name = $row['name'];
                unset($row['name']);
                $result[$name] = $row;
            }
        }
        return $result;
    }

    /**
     * Initialize the storage
     *
     * @return void
     */
    protected function initialize()
    {
        if (!$this->initialized) {
            $this->doInitialize();
            $this->initialized = true;
        }
    }

    /**
     * Initializes DB statements
     *
     * @return void
     */
    protected function doInitialize()
    {
        $pdo = Environment::initRefDb();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->stmtReleases = $pdo->prepare(
            'SELECT rel_date as "date", rel_state as "state",' .
            ' rel_version as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max"' .
            ' FROM bartlett_compatinfo_releases r,  bartlett_compatinfo_extensions e' .
            ' WHERE r.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtIniEntries = $pdo->prepare(
            'SELECT i.name,' .
            ' ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max",' .
            ' deprecated' .
            ' FROM bartlett_compatinfo_inientries i,  bartlett_compatinfo_extensions e' .
            ' WHERE i.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtClasses = $pdo->prepare(
            'SELECT c.name,' .
            ' e.name as "ext.name", ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max"' .
            ' FROM bartlett_compatinfo_classes c,  bartlett_compatinfo_extensions e' .
            ' WHERE c.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtClassMethods = $pdo->prepare(
            'SELECT class_name, m.name, static,' .
            ' e.name as "ext.name", ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max"' .
            ' FROM bartlett_compatinfo_methods m,  bartlett_compatinfo_extensions e' .
            ' WHERE m.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtClassConstants = $pdo->prepare(
            'SELECT class_name, c.name,' .
            ' e.name as "ext.name", ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max",' .
            ' optional' .
            ' FROM bartlett_compatinfo_const c,  bartlett_compatinfo_extensions e' .
            ' WHERE c.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtInterfaces = $pdo->prepare(
            'SELECT i.name,' .
            ' e.name as "ext.name", ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max"' .
            ' FROM bartlett_compatinfo_interfaces i,  bartlett_compatinfo_extensions e' .
            ' WHERE i.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtFunctions = $pdo->prepare(
            'SELECT f.name,' .
            ' e.name as "ext.name", ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max",' .
            ' parameters, php_excludes as "php.excludes",' .
            ' deprecated,' .
            ' lib_curl' .
            ' FROM bartlett_compatinfo_functions f,  bartlett_compatinfo_extensions e' .
            ' WHERE f.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtConstants = $pdo->prepare(
            'SELECT c.name,' .
            ' e.name as "ext.name", ext_min as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max",' .
            ' php_excludes as "php.excludes",' .
            ' optional,' .
            ' lib_curl' .
            ' FROM bartlett_compatinfo_constants c,  bartlett_compatinfo_extensions e' .
            ' WHERE c.ext_name_fk = e.id AND e.name = :name COLLATE NOCASE'
        );

        $this->stmtExtensions = $pdo->prepare(
            'SELECT e.name, rel_date as "date", rel_state as "state",' .
            ' rel_version as "ext.min", ext_max as "ext.max",' .
            ' php_min as "php.min", php_max as "php.max"' .
            ' FROM bartlett_compatinfo_releases r,  bartlett_compatinfo_extensions e' .
            ' WHERE r.ext_name_fk = e.id' .
            ' ORDER BY e.name asc, date desc, rel_version desc'
        );
    }
}

<?php
/**
 * Reference Interface.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @version  GIT: $Id$
 * @link     http://php5.laurent-laville.org/compatinfo/
 */

namespace Bartlett\CompatInfoDb;

/**
 * Interface that define a reference (extension).
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @version  Release: @package_version@
 * @link     http://php5.laurent-laville.org/compatinfo/
 * @since    Class available since Release 3.0.0RC1 of PHP_CompatInfo
 * @since    Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
interface ReferenceInterface
{
    /**
     * Gets the current version of extension loaded on the platform
     *
     * @return mixed FALSE if extension not loaded, otherwise string
     */
    public function getCurrentVersion();

    /**
     * Gets the latest (release) version of an extension.
     *
     * @return string
     */
    public function getLatestVersion();

    /**
     * Gets a list of releases from an extension.
     *
     * @return array
     */
    public function getReleases();

    /**
     * Gets a list of interfaces from an extension.
     *
     * @return array
     */
    public function getInterfaces();

    /**
     * Gets a list of classes from an extension.
     *
     * @return array
     */
    public function getClasses();

    /**
     * Gets a list of functions from an extension.
     *
     * @return array
     */
    public function getFunctions();

    /**
     * Gets a list of constants from an extension.
     *
     * @return array
     */
    public function getConstants();

    /**
     * Gets a list of ini entries from an extension.
     *
     * @return array
     */
    public function getIniEntries();

    /**
     * Gets a list of class constants from an extension.
     *
     * @return array
     */
    public function getClassConstants();

    /**
     * Gets a list of static class methods from an extension.
     *
     * @return array
     */
    public function getClassStaticMethods();

    /**
     * Gets a list of non-static class methods from an extension.
     *
     * @return array
     */
    public function getClassMethods();
}

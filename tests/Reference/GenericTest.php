<?php
/**
 * Unit tests for PHP_CompatInfo, Generic extension base class.
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    GIT: $Id$
 * @link       http://php5.laurent-laville.org/compatinfo/
 */

namespace Bartlett\Tests\CompatInfoDb\Reference;

use Bartlett\CompatInfoDb\ReferenceInterface;
use Bartlett\CompatInfoDb\ExtensionFactory;

use Composer\Semver\Semver;

/**
 * Tests for the PHP_CompatInfo, retrieving components informations
 * about any extension.
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0RC1 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
abstract class GenericTest extends \PHPUnit_Framework_TestCase
{
    const REF_ELEMENT_INI       = 1;
    const REF_ELEMENT_CONSTANT  = 2;
    const REF_ELEMENT_FUNCTION  = 3;
    const REF_ELEMENT_INTERFACE = 4;
    const REF_ELEMENT_CLASS     = 5;

    protected static $obj = null;
    protected static $ref = null;
    protected static $ext = null;

    // Could be defined in Reference but missing (system dependant)
    protected static $optionalreleases    = array();
    protected static $optionalcfgs        = array();
    protected static $optionalconstants   = array();
    protected static $optionalfunctions   = array();
    protected static $optionalclasses     = array();
    protected static $optionalinterfaces  = array();

    // Could be present but missing in Reference (alias, ...)
    protected static $ignoredcfgs          = array();
    protected static $ignoredconstants     = array();
    protected static $ignoredfunctions     = array();
    protected static $ignoredclasses       = array();
    protected static $ignoredinterfaces    = array();

    // References fully documented
    protected static $extensions =  array(
        'amqp',
        'date',
        'gender',
        'haru',
        'htscanner',
        'imagick',
        'igbinary',
        'inclued',
        'intl',
        'jsmin',
        'lzf',
        'mailparse',
        'mongo',
        'msgpack',
        'oauth',
        'pdflib',
        'pthreads',
        'rar',
        'redis',
        'reflection',
        'riak',
        'solr',
        'ssh2',
        'sphinx',
        'spl',
        'stomp',
        'uopz',
        'uploadprogress',
        'varnish',
        'xdebug',
        'xmldiff',
        'Zend OPcache',
        'zip',
    );

    /**
     * {@inheritDoc}
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $parts = explode('\\', get_class($this));

        self::$ext = $name = strtolower(
            str_replace('ExtensionTest', '', end($parts))
        );

        // special case(s)
        if ('zendopcache' === $name) {
            self::$ext = $name = 'zend opcache';
        }

        self::$obj = new ExtensionFactory($name);
    }

    /**
     * Sets up the shared fixture.
     *
     * @return void
     * @link   http://phpunit.de/manual/current/en/fixtures.html#fixtures.sharing-fixture
     */
    public static function setUpBeforeClass()
    {
        self::$optionalreleases = array();

        $releases       = array_keys(self::$obj->getReleases());
        $currentVersion = self::$obj->getCurrentVersion();

        if ($currentVersion === false) {
            // extension did not provide any version information
            return;
        }

        // platform dependant
        foreach ($releases as $rel_version) {
            if (version_compare($currentVersion, $rel_version, 'lt')) {
                array_push(self::$optionalreleases, $rel_version);
            }
        }
    }

    /**
     * Generic Reference validator and producer
     *
     * @return array()
     */
    public function provideReferenceValues($methodName)
    {
        if (!extension_loaded(self::$ext)) {
            $this->markTestSkipped(
                sprintf('Extension %s is required.', self::$ext)
            );
        }

        if ('testGetIniEntriesFromReference' === $methodName) {
            $elements = self::$obj->getIniEntries();
            $opt = 'optionalcfgs';

        } elseif ('testGetFunctionsFromReference'  === $methodName) {
            $elements = self::$obj->getFunctions();
            $opt = 'optionalfunctions';

        } elseif ('testGetConstantsFromReference'  === $methodName) {
            $elements = self::$obj->getConstants();
            $opt = 'optionalconstants';

        } elseif ('testGetClassesFromReference' == $methodName) {
            $elements = self::$obj->getClasses();
            $opt = 'optionalclasses';

        } elseif ('testGetInterfacesFromReference' == $methodName) {
            $elements = self::$obj->getInterfaces();
            $opt = 'optionalinterfaces';

        } else {
            $elements = array();
        }

        $this->assertTrue(is_array($elements));

        if (empty($elements)) {
            // do not fails suite if no test case found in data provider
            $this->markTestSkipped(
                sprintf(
                    'No tests found in suite "%s::%s".',
                    get_class($this),
                    $methodName
                )
            );
        }

        $dataset = array();
        foreach ($elements as $name => $range) {
            if (!empty($range['optional'])) {
                self::${$opt}[] = $name;
                continue;
            }

            $libs = array();
            foreach ($range as $key => $val) {
                if (strpos($key, 'lib_') === 0) {
                    if (!empty($val)) {
                        $libs[$key] = $val;
                    }
                }
            }

            foreach($libs as $lib => $constraint) {
                $lib = str_replace('lib_', '', $lib);
                $ver = self::lib($lib, 'version_text');

                if (!Semver::satisfies($ver, $constraint)) {
                    self::${$opt}[] = $name;
                    continue 2;
                }
            }
            $dataset[] = array($name, $range);
        }
        return $dataset;
    }

    protected static function lib($name, $key = 'version_number')
    {
        if ('curl' == $name
            && function_exists('curl_version')
        ) {
            $meta = curl_version();
            $meta['version_text'] = $meta['version'];

        } elseif ('libxml' == $name) {
            $meta = array(
                'version_number' => defined('LIBXML_DOTTED_VERSION')
                    ? self::toNumber(\LIBXML_DOTTED_VERSION) : false,
                'version_text'   => defined('LIBXML_DOTTED_VERSION')
                    ? \LIBXML_DOTTED_VERSION : false,
            );

        } elseif ('intl' == $name) {
            $meta = array(
                'version_number' => defined('INTL_ICU_VERSION')
                    ? self::toNumber(\INTL_ICU_VERSION) : false,
                'version_text'   => defined('INTL_ICU_VERSION')
                    ? \INTL_ICU_VERSION : false,
            );

        } elseif ('openssl' == $name) {
            $meta = array(
                'version_number' => defined('OPENSSL_VERSION_NUMBER')
                    ? \OPENSSL_VERSION_NUMBER : false,
                'version_text'   => defined('OPENSSL_VERSION_TEXT')
                    ? self::toText(\OPENSSL_VERSION_NUMBER) : false,
            );

        } elseif ('imagemagick' == $name) {
            if (method_exists('Imagick', 'getVersion')) {
                $v = \Imagick::getVersion();
                if (preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $v['versionString'], $matches)) {
                    $meta = array(
                        'version_number' => $v['versionNumber'],
                        'version_text'   => $matches[1],
                    );
                }
            }
        }
        if (isset($meta)) {
            if (isset($key) && array_key_exists($key, $meta)) {
                return $meta[$key];
            }
            return $meta;
        }
        return false;
    }

    protected static function toText($number)
    {
        $hex = dechex(($number & ~ 15) / 16);

        if (strlen($hex) % 2 !== 0) {
            $hex = '0' . $hex;
        }

        $arr = str_split($hex, 2);

        return implode('.', array_map('hexdec', $arr));
    }

    protected static function toNumber($text)
    {
        $arr = explode('.', $text);
        $arr = array_map('dechex', $arr);
        $hex = '';

        foreach ($arr as $digit) {
            if (strlen($digit) % 2 !== 0) {
                $hex .= '0';
            }
            $hex .= $digit;
        }
        $hex .= 'F';

        return hexdec($hex);
    }

    /**
     *
     */
    protected function checkValuesFromReference($element, $range, &$optional, &$ignored, $refElementType)
    {
        if (in_array($range['ext.min'], self::$optionalreleases)) {
            return;
        }

        $min = $range['php.min'];
        $max = $range['php.max'];

        if (array_key_exists('php.excludes', $range)) {
            if (in_array(PHP_VERSION, $range['php.excludes'])) {
                // We are in min/max, so add it as optional
                array_push($optional, $element);
            }
        }
        if (!in_array($element, $optional)
            && (empty($min) || version_compare(PHP_VERSION, $min) >= 0)
            && (empty($max) || version_compare(PHP_VERSION, $max) <= 0)
        ) {
            // Should be there except if set as optional
            $this->assertShouldBeThere($element, $refElementType);
        }
        if (!in_array($element, $ignored)) {
            if (($min && version_compare(PHP_VERSION, $min) < 0)
                || ($max && version_compare(PHP_VERSION, $max) > 0)
            ) {
                // Should not be there except if ignored
                $this->assertShouldNotBeThere($element, $refElementType, $min, $max);
            }
        }
    }

    protected function assertShouldBeThere($element, $refElementType)
    {
        if (self::REF_ELEMENT_INI == $refElementType) {
            $this->assertNotSame(
                ini_get($element),
                false,
                "INI '$element', found in Reference, does not exists."
            );

        } elseif (self::REF_ELEMENT_FUNCTION == $refElementType) {
            $this->assertTrue(
                function_exists($element),
                "Function '$element', found in Reference, does not exists."
            );

        } elseif (self::REF_ELEMENT_CONSTANT == $refElementType) {
            $this->assertTrue(
                defined($element),
                "Constant '$element', found in Reference, does not exists."
            );

        } elseif (self::REF_ELEMENT_CLASS == $refElementType) {
            $this->assertTrue(
                class_exists($element, false),
                "Class '$element', found in Reference, does not exists."
            );

        } elseif (self::REF_ELEMENT_INTERFACE == $refElementType) {
            $this->assertTrue(
                interface_exists($element, false),
                "Interface '$element', found in Reference, does not exists."
            );
        }
    }

    protected function assertShouldNotBeThere($element, $refElementType, $min, $max)
    {
        if (self::REF_ELEMENT_INI == $refElementType) {
            $this->assertFalse(
                ini_get($element),
                "INI '$element', found in Reference ($min,$max), exists."
            );

        } elseif (self::REF_ELEMENT_FUNCTION == $refElementType) {
            $this->assertFalse(
                function_exists($element),
                "Function '$element', found in Reference ($min,$max), exists."
            );

        } elseif (self::REF_ELEMENT_CONSTANT == $refElementType) {
            $this->assertFalse(
                defined($element),
                "Constant '$element', found in Reference ($min,$max), exists."
            );

        } elseif (self::REF_ELEMENT_CLASS == $refElementType) {
            $this->assertFalse(
                class_exists($element, false),
                "Class '$element', found in Reference ($min,$max), exists."
            );

        } elseif (self::REF_ELEMENT_INTERFACE == $refElementType) {
            $this->assertFalse(
                interface_exists($element, false),
                "Interface '$element', found in Reference ($min,$max), exists."
            );
        }
    }

    /**
     * Test that a reference exists and provides releases
     * @group  reference
     * @return void
     */
    public function testReference()
    {
        if (!extension_loaded(self::$ext)) {
            $this->markTestSkipped(
                sprintf('Extension %s is required.', self::$ext)
            );
        }
        $this->assertTrue(true);
    }

    /**
     * Test than all referenced ini entries exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromReference($name, $range)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            self::$optionalcfgs,
            self::$ignoredcfgs,
            self::REF_ELEMENT_INI
        );
    }

    /**
     * Test that each ini entries are defined in reference
     *
     * @depends testReference
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromExtension()
    {
        $extname = self::$ext;

        if ('internal' == $extname) {
            // only Core is a valid extension name for API reflection
            return;
        }
        $dict       = self::$obj->getIniEntries();
        $extension  = new \ReflectionExtension($extname);
        $iniEntries = array_keys($extension->getINIEntries());
        $this->assertTrue(is_array($dict));

        foreach ($iniEntries as $iniEntry) {
            if (!in_array($iniEntry, self::$ignoredcfgs)) {
                $this->assertArrayHasKey(
                    $iniEntry,
                    $dict,
                    "Defined INI '$iniEntry' not known in Reference."
                );
            }
        }
    }

    /**
     * Test than all referenced functions exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromReference($name, $range)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            self::$optionalfunctions,
            self::$ignoredfunctions,
            self::REF_ELEMENT_FUNCTION
        );
    }

    /**
     * Test that each functions are defined in reference
     *
     * @depends testReference
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromExtension()
    {
        $ext = get_extension_funcs(self::$ext);
        if (!is_array($ext)) {
            // can be NULL for ext without function
            $ext = array();
        }
        $dict = self::$obj->getFunctions();
        $this->assertTrue(is_array($dict));

        foreach ($ext as $fctname) {
            if (!in_array($fctname, self::$ignoredfunctions)) {
                $this->assertArrayHasKey(
                    $fctname,
                    $dict,
                    "Defined function '$fctname' not known in Reference."
                );
            }
        }
    }

    /**
     * Test than all referenced constants exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromReference($name, $range)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            self::$optionalconstants,
            self::$ignoredconstants,
            self::REF_ELEMENT_CONSTANT
        );
    }

    /**
     * Test that each constants are defined in reference
     *
     * @depends testReference
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromExtension()
    {
        $extname = self::$ext;
        $const   = get_defined_constants(true);
        $dict    = self::$obj->getConstants();
        $this->assertTrue(is_array($dict));

        if (defined('__PHPUNIT_PHAR__')) {
            // remove '' . "\0" . '__COMPILER_HALT_OFFSET__' . "\0" . __PHPUNIT_PHAR__
            array_pop($const['Core']);
        }

        if (isset($const[$extname])) {
            // Test if each constants are in reference
            foreach ($const[$extname] as $constname => $value) {
                if (!in_array($constname, self::$ignoredconstants)) {
                    $this->assertArrayHasKey(
                        $constname,
                        $dict,
                        "Defined constant '$constname' not known in Reference."
                    );
                }
            }
        }
    }

    /**
     * Test than all referenced classes exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetClassesFromReference($name, $range)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            self::$optionalclasses,
            self::$ignoredclasses,
            self::REF_ELEMENT_CLASS
        );
    }

    /**
     * Test that each classes are defined in reference
     *
     * @depends testReference
     * @group  reference
     * @return void
     */
    public function testGetClassesFromExtension()
    {
        $extname = self::$ext;

        if ('internal' == $extname) {
            // only Core is a valid extension name for API reflection
            return;
        }
        $dict1     = self::$obj->getClasses();
        $dict2     = self::$obj->getInterfaces();
        $extension = new \ReflectionExtension($extname);
        $classes   = $extension->getClassNames();
        $this->assertTrue(is_array($classes));

        foreach ($classes as $classname) {
            if (class_exists($classname)) {
                if (!in_array($classname, self::$ignoredclasses)) {
                    $this->assertArrayHasKey(
                        $classname,
                        $dict1,
                        "Defined class '$classname' not known in Reference."
                    );
                }
            } else {
                if (!in_array($classname, self::$ignoredinterfaces)) {
                    $this->assertArrayHasKey(
                        $classname,
                        $dict2,
                        "Defined interface '$classname' not known in Reference."
                    );
                }
            }
        }
    }

    /**
     * Test that each class methods are defined in reference
     *
     * @depends testReference
     * @group  reference
     * @return void
     */
    public function testGetClassMethodsFromExtension()
    {
        if (!in_array(self::$ext, self::$extensions)) {
            $this->assertFalse(false);
            return;
        }

        $extname   = self::$ext;
        $extension = new \ReflectionExtension($extname);
        $classes   = array_unique($extension->getClassNames());
        $this->assertTrue(is_array($classes));

        $nonStaticMethods = self::$obj->getClassMethods();
        $staticMethods    = self::$obj->getClassStaticMethods();

        foreach ($classes as $classname) {
            $class   = new \ReflectionClass($classname);
            if ($class->getName() != $classname) {
                /* Skip class alias */
                continue;
            }
            $methods = $class->getMethods();

            foreach ($methods as $method) {
                if (!$method->isPublic()) {
                    continue;
                }
                $from = $method->getDeclaringClass()->getName();

                if ($from !== $classname) {
                    // don't check inherit methods
                    continue;
                }
                try {
                    $method->getPrototype();
                    // don't check prototype methods
                    continue;
                } catch (\ReflectionException $e) {
                }
                $methodname = $method->getName();
                if ($method->isStatic()) {
                    $this->assertArrayHasKey(
                        $classname,
                        $staticMethods,
                        "Defined static method '$classname::$methodname' not known in Reference."
                    );
                    $this->assertArrayHasKey(
                        $methodname,
                        $staticMethods[$classname],
                        "Defined static method '$classname::$methodname' not known in Reference."
                    );
                } else {
                    $this->assertArrayHasKey(
                        $classname,
                        $nonStaticMethods,
                        "Defined method '$classname::$methodname' not known in Reference."
                    );
                    $this->assertArrayHasKey(
                        $methodname,
                        $nonStaticMethods[$classname],
                        "Defined method '$classname::$methodname' not known in Reference."
                    );
                }
            }
        }
    }

    /**
     * Test that each class constants are defined in reference
     *
     * @depends testReference
     * @group  reference
     * @return void
     */
    public function testGetClassConstantsFromExtension()
    {
        if (!in_array(self::$ext, self::$extensions)) {
            $this->assertFalse(false);
            return;
        }

        $extname   = self::$ext;
        $extension = new \ReflectionExtension($extname);
        $classes   = array_unique($extension->getClassNames());
        $this->assertTrue(is_array($classes));

        $classconstants = self::$obj->getClassConstants();

        foreach ($classes as $classname) {
            $class   = new \ReflectionClass($classname);
            if ($class->getName() != $classname) {
                /* Skip class alias */
                continue;
            }

            $parent = $class->getParentClass();
            if ($parent) {
                $constants = array();
            } else {
                $constants = $class->getConstants();
            }

            if (!array_key_exists($classname, $classconstants)) {
                $classconstants[$classname] = array();
            }

            foreach ($constants as $constantname => $constantvalue) {
                $this->assertArrayHasKey(
                    $constantname,
                    $classconstants[$classname],
                    "Defined class constant '$classname::$constantname' not known in Reference."
                );
            }
        }
    }

    /**
     * Test than all referenced interfaces exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromReference($name, $range)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            self::$optionalinterfaces,
            self::$ignoredinterfaces,
            self::REF_ELEMENT_INTERFACE
        );
    }
}

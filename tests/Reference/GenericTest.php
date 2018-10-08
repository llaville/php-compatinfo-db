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
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 */

namespace Bartlett\Tests\CompatInfoDb\Reference;

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
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0RC1 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
abstract class GenericTest extends \PHPUnit\Framework\TestCase
{
    const REF_ELEMENT_INI       = 1;
    const REF_ELEMENT_CONSTANT  = 2;
    const REF_ELEMENT_FUNCTION  = 3;
    const REF_ELEMENT_INTERFACE = 4;
    const REF_ELEMENT_CLASS     = 5;
    const REF_ELEMENT_METHOD    = 6;
    const REF_ELEMENT_CONST     = 7;  // class constant

    protected static $obj = null;
    protected static $ref = null;
    protected static $ext = null;

    protected $extension;

    // Could be defined in Reference but missing (system dependant)
    protected static $optionalreleases    = array();
    protected static $optionalcfgs        = array();
    protected static $optionalconstants   = array();
    protected static $optionalfunctions   = array();
    protected static $optionalclasses     = array();
    protected static $optionalinterfaces  = array();
    protected static $optionalmethods     = array();

    // Could be present but missing in Reference (alias, ...)
    protected static $ignoredcfgs          = array();
    protected static $ignoredconstants     = array();
    protected static $ignoredfunctions     = array();
    protected static $ignoredclasses       = array();
    protected static $ignoredinterfaces    = array();
    protected static $ignoredmethods       = array();
    protected static $ignoredconsts        = array();

    // References fully documented
    protected static $extensions =  array(
        'amqp',
        'date',
        'exif',
        'gender',
        'geoip',
        'gmp',
        'haru',
        'htscanner',
        'imagick',
        'igbinary',
        'inclued',
        'intl',
        'jsmin',
        'ldap',
        'lzf',
        'mailparse',
        'mongo',
        'msgpack',
        'mysqli',
        'oauth',
        'openssl',
        'pdflib',
        'pgsql',
        'pthreads',
        'rar',
        'redis',
        'reflection',
        'riak',
        'soap',
        'sockets',
        'solr',
        'sphinx',
        'spl',
        'sqlite3',
        'ssh2',
        'stomp',
        'sync',
        'tidy',
        'uopz',
        'uploadprogress',
        'varnish',
        'xdebug',
        'xmldiff',
        'xmlrpc',
        'xsl',
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

        self::$ext = $this->extension = $name;
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

        if (!is_object(self::$obj) || self::$obj->getName() !== self::$ext) {
            self::$obj = new ExtensionFactory(self::$ext);
        }

        $currentVersion = self::$obj->getCurrentVersion();

        if ($currentVersion === false) {
            // extension did not provide any version information
            return;
        }

        $releases = array_keys(self::$obj->getReleases());

        // platform dependant
        foreach ($releases as $rel_version) {
            if (version_compare($currentVersion, $rel_version, 'lt')) {
                array_push(self::$optionalreleases, $rel_version);
            }
        }
    }

    public static function tearDownAfterClass()
    {
        self::$optionalreleases   = [];
        self::$optionalcfgs       = [];
        self::$optionalconstants  = [];
        self::$optionalfunctions  = [];
        self::$optionalclasses    = [];
        self::$optionalinterfaces = [];
        self::$optionalmethods    = [];

        self::$ignoredcfgs        = [];
        self::$ignoredconstants   = [];
        self::$ignoredfunctions   = [];
        self::$ignoredclasses     = [];
        self::$ignoredinterfaces  = [];
        self::$ignoredmethods     = [];
        self::$ignoreconsts       = [];
    }

    /**
     * Generic Reference validator and producer
     */
    public function provideReferenceValues($methodName)
    {
        static $obj;

        $this->checkExtension();

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
        }

        if ('testGetIniEntriesFromReference' === $methodName) {
            $refElementType = self::REF_ELEMENT_INI;
            $elements = $obj->getIniEntries();
            $opt = 'optionalcfgs';

        } elseif ('testGetFunctionsFromReference'  === $methodName) {
            $refElementType = self::REF_ELEMENT_FUNCTION;
            $elements = $obj->getFunctions();
            $opt = 'optionalfunctions';

        } elseif ('testGetConstantsFromReference'  === $methodName) {
            $refElementType = self::REF_ELEMENT_CONSTANT;
            $elements = $obj->getConstants();
            $opt = 'optionalconstants';

        } elseif ('testGetClassesFromReference' === $methodName) {
            $refElementType = self::REF_ELEMENT_CLASS;
            $elements = $obj->getClasses();
            $opt = 'optionalclasses';

        } elseif ('testGetInterfacesFromReference' === $methodName) {
            $refElementType = self::REF_ELEMENT_INTERFACE;
            $elements = $obj->getInterfaces();
            $opt = 'optionalinterfaces';

        } elseif ('testGetClassMethodsFromReference' === $methodName) {
            $refElementType = self::REF_ELEMENT_METHOD;
            $elements = [];

            $methods = array_merge(
                $obj->getClassMethods(),
                $obj->getClassStaticMethods()
            );

            foreach ($methods as $class => $values) {
                foreach ($values as $method => $range) {
                    $elements[$class.'::'.$method] = $range;
                }
            }
            $opt = 'optionalmethods';

        } else {
            $elements = [];
        }

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
            yield [$name, $range, $refElementType];
        }
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
    protected function checkValuesFromReference($element, $range, $refElementType)
    {
        if (in_array($range['ext.min'], self::$optionalreleases)) {
            return;
        }

        if (self::REF_ELEMENT_INI == $refElementType) {
            $optional = self::$optionalcfgs;
            $ignored = self::$ignoredcfgs;
        } elseif (self::REF_ELEMENT_CONSTANT == $refElementType) {
            $optional = self::$optionalconstants;
            $ignored = self::$ignoredconstants;
        } elseif (self::REF_ELEMENT_FUNCTION == $refElementType) {
            $optional = self::$optionalfunctions;
            $ignored = self::$ignoredfunctions;
        } elseif (self::REF_ELEMENT_INTERFACE == $refElementType) {
            $optional = self::$optionalinterfaces;
            $ignored = self::$ignoredinterfaces;
        } elseif (self::REF_ELEMENT_CLASS == $refElementType) {
            $optional = self::$optionalclasses;
            $ignored = self::$ignoredclasses;
        } elseif (self::REF_ELEMENT_METHOD == $refElementType) {
            $optional = self::$optionalmethods;
            $ignored = self::$ignoredmethods;
        }

        $min = $range['php.min'];
        $max = $range['php.max'];

        $EXT_VERSION = self::$obj->getCurrentVersion();

        $emin = $range['ext.min'];
        $emax = $range['ext.max'];

        if (array_key_exists('php.excludes', $range)) {
            if (in_array(PHP_VERSION, $range['php.excludes'])) {
                // We are in min/max, so add it as optional
                array_push($optional, $element);
            }
        }
        if (!in_array($element, $optional)
            && (empty($min) || version_compare(PHP_VERSION, $min) >= 0)
            && (empty($max) || version_compare(PHP_VERSION, $max) <= 0)
            && (!empty($emin) && version_compare($EXT_VERSION, $emin) >= 0)
            && (!empty($emax) && version_compare($EXT_VERSION, $emax) <= 0)
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

        } elseif (self::REF_ELEMENT_METHOD == $refElementType) {
            list ($object, $method) = explode('::', $element);
            $this->assertTrue(
                method_exists($object, $method),
                "Class Method '$element', found in Reference, does not exists."
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

        } elseif (self::REF_ELEMENT_METHOD == $refElementType) {
            list ($object, $method) = explode('::', $element);
            $this->assertFalse(
                method_exists($object, $method),
                "Class Method '$element', found in Reference, exists."
            );
        }
    }

    /**
     * Generic Extension validator and producer
     */
    public function provideExtensionValues($methodName)
    {
        $this->checkExtension();

        if ('testGetConstantsFromExtension' === $methodName) {
            return $this->constantsFromExtensionProvider();

        } elseif ('testGetClassConstantsFromExtension' === $methodName) {
            return $this->classConstantsFromExtensionProvider();

        } elseif ('testGetIniEntriesFromExtension' === $methodName) {
            return $this->iniEntriesFromExtensionProvider();

        } elseif ('testGetFunctionsFromExtension' == $methodName) {
            return $this->functionsFromExtensionProvider();

        } elseif ('testGetClassesFromExtension' === $methodName) {
            return $this->classesFromExtensionProvider();

        } elseif ('testGetInterfacesFromExtension' === $methodName) {
            return $this->interfacesFromExtensionProvider();

        } elseif ('testGetClassMethodsFromExtension' === $methodName) {
            return $this->classMethodsFromExtensionProvider();
        }
    }

    /**
     * Provider to get INI entries from an extension
     */
    public function iniEntriesFromExtensionProvider()
    {
        $extension = new \ReflectionExtension(self::$ext);
        $elements  = array_keys($extension->getINIEntries());

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Provider to get constants from an extension
     */
    public function constantsFromExtensionProvider()
    {
        $constants = get_defined_constants(true);

        if (defined('__PHPUNIT_PHAR__')) {
            // remove '' . "\0" . '__COMPILER_HALT_OFFSET__' . "\0" . __PHPUNIT_PHAR__
            array_pop($constants['Core']);
        }

        $elements = isset($constants[self::$ext]) ? array_keys($constants[self::$ext]) : [];

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Provider to get functions from extension
     */
    public function functionsFromExtensionProvider()
    {
        $elements = get_extension_funcs(strtolower(self::$ext));
        if (!is_array($elements)) {
            // can be NULL for ext without function
            $elements = [];
        }

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Provider to get classes from extension
     */
    public function classesFromExtensionProvider()
    {
        $extension = new \ReflectionExtension(self::$ext);
        $classes   = array_unique($extension->getClassNames());
        $elements  = array_filter($classes, 'class_exists');

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Provider to get interfaces from extension
     */
    public function interfacesFromExtensionProvider()
    {
        $extension = new \ReflectionExtension(self::$ext);
        $classes   = array_unique($extension->getClassNames());
        $elements  = array_filter($classes, 'interface_exists');

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Provider to get class constants from extension
     */
    public function classConstantsFromExtensionProvider()
    {
        $elements = [];

        if (in_array(self::$ext, self::$extensions)) {
            $extension = new \ReflectionExtension(self::$ext);
            $classes   = array_unique($extension->getClassNames());

            foreach ($classes as $classname) {
                $class = new \ReflectionClass($classname);
                if ($class->getName() != $classname) {
                    /* Skip class alias */
                    continue;
                }

                $elements = $elements + array_map(
                    function ($value) use ($classname) {
                        return "$classname::$value";
                    },
                    array_keys($class->getConstants())
                );
            }
        }

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Provider to get class methods from extension
     */
    public function classMethodsFromExtensionProvider()
    {
        $extension = new \ReflectionExtension(self::$ext);
        $classes   = array_unique($extension->getClassNames());

        $elements = [];

        foreach ($classes as $classname) {
            $class   = new \ReflectionClass($classname);
            if ($class->getName() != $classname) {
                /* Skip class alias */
                continue;
            }

            foreach ($class->getMethods() as $method) {
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

                $elements[] = $classname . '::' . $method->getName();
            }
        }

        foreach ($elements as $name) {
            yield [$name];
        }
    }

    /**
     * Check that a reference exists and initialize an instance
     *
     * @return void
     */
    public function checkExtension()
    {
        $parts = explode('\\', get_called_class());

        self::$ext = $name = strtolower(
            str_replace('ExtensionTest', '', end($parts))
        );

        // special case(s)
        if ('zendopcache' === $name) {
            self::$ext = $name = 'zend opcache';
        }

        if (!extension_loaded(self::$ext)) {
            $this->markTestSkipped(
                sprintf('Extension %s is required.', self::$ext)
            );
        }
    }

    /**
     * Test than all referenced ini entries exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromReference($name, $range, $refElementType)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            $refElementType
        );
    }

    /**
     * Test that each ini entries are defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromExtension($name)
    {
        static $obj;
        static $dict;

        if ('internal' == self::$ext) {
            // only Core is a valid extension name for API reflection
            return;
        }

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $dict = $obj->getIniEntries();
            $this->assertTrue(is_array($dict));
        }

        if (!in_array($name, self::$ignoredcfgs)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined INI '$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced functions exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromReference($name, $range, $refElementType)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            $refElementType
        );
    }

    /**
     * Test that each functions are defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromExtension($name)
    {
        static $obj;
        static $dict;

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $dict = $obj->getFunctions();
            $this->assertTrue(is_array($dict));
        }

        if (!in_array($name, self::$ignoredfunctions)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined function '$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced constants exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromReference($name, $range, $refElementType)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            $refElementType
        );
    }

    /**
     * Test that each constants are defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function _testGetConstantsFromExtension($name)
    {
        static $obj;
        static $dict;

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $dict = $obj->getConstants();
            $this->assertTrue(is_array($dict));
        }

        // Test if each constants are in reference
        if (!in_array($name, self::$ignoredconstants)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined constant '$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced classes exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetClassesFromReference($name, $range, $refElementType)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            $refElementType
        );
    }

    /**
     * Test that each classes are defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function _testGetClassesFromExtension($name)
    {
        static $obj;
        static $dict;

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $dict = $obj>getClasses();
            $this->assertTrue(is_array($dict));
        }

        if (!in_array($name, self::$ignoredclasses)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined class '$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced class methods exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetClassMethodsFromReference($name, $range, $refElementType)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            $refElementType
        );
    }

    /**
     * Test that each class methods are defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function _testGetClassMethodsFromExtension($name)
    {
        static $obj;
        static $nonStaticMethods;
        static $staticMethods;

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $nonStaticMethods = $obj->getClassMethods();
            $this->assertTrue(is_array($nonStaticMethods));
            $staticMethods    = $obj->getClassStaticMethods();
            $this->assertTrue(is_array($staticMethods));
        }

        list ($classname, $name) = explode('::', $name);

        if (isset($nonStaticMethods[$classname])) {
            $this->assertArrayHasKey(
                $name,
                $nonStaticMethods[$classname],
                "Defined method '$classname::$name' not known in Reference."
            );
        } else {
            $this->assertArrayHasKey(
                $name,
                $staticMethods[$classname],
                "Defined static method '$classname::$name' not known in Reference."
            );
        }
    }

    /**
     * Test that each class constants are defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function testGetClassConstantsFromExtension($name)
    {
        static $obj;
        static $dict;

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $dict = $obj->getClassConstants();
            $this->assertTrue(is_array($dict));
        }

        list ($classname, $name) = explode('::', $name);

        if (!in_array($name, self::$ignoredconsts)) {
            $this->assertArrayHasKey(
                $name,
                $dict[$classname],
                "Defined class constant '$classname::$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced interfaces exists
     *
     * @dataProvider provideReferenceValues
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromReference($name, $range, $refElementType)
    {
        $this->checkValuesFromReference(
            $name,
            $range,
            $refElementType
        );
    }

    /**
     * Test that each interface is defined in reference
     *
     * @dataProvider provideExtensionValues
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromExtension($name)
    {
        static $obj;
        static $dict;

        if (!is_object($obj) || $obj->getName() !== $this->extension) {
            $obj = new ExtensionFactory($this->extension);
            $dict = $obj->getInterfaces();
            $this->assertTrue(is_array($dict));
        }

        if (!in_array($name, self::$ignoredinterfaces)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined interface '$name' not known in Reference."
            );
        }
    }
}

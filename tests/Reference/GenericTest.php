<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, Generic extension base class.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Tests\Reference;

use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

use Composer\Semver\Semver;

use PHPUnit\Framework\ExpectationFailedException;

use Generator;
use ReflectionClass;
use ReflectionException;
use ReflectionExtension;
use ReflectionFunction;
use ReflectionMethod;
use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_pop;
use function array_push;
use function array_unique;
use function class_exists;
use function dechex;
use function defined;
use function dirname;
use function end;
use function explode;
use function extension_loaded;
use function get_called_class;
use function get_defined_constants;
use function get_extension_funcs;
use function hexdec;
use function implode;
use function in_array;
use function ini_get;
use function interface_exists;
use function is_array;
use function phpversion;
use function sprintf;
use function str_replace;
use function str_split;
use function strcasecmp;
use function strlen;
use function strtolower;
use function version_compare;
use const DIRECTORY_SEPARATOR;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
abstract class GenericTest extends TestCase implements ExtensionVersionProviderInterface
{
    use LibraryVersionProviderTrait;
    use ExtensionVersionProviderTrait;

    protected static $obj = null;

    // Could be defined in Reference but missing (system dependant)
    protected static $optionalreleases    = [];
    protected static $optionalcfgs        = [];
    protected static $optionalconstants   = [];
    protected static $optionalfunctions   = [];
    protected static $optionalclasses     = [];
    protected static $optionalinterfaces  = [];
    protected static $optionalmethods     = [];

    // Could be present but missing in Reference (alias, ...)
    protected static $ignoredcfgs          = [];
    protected static $ignoredconstants     = [];
    protected static $ignoredfunctions     = [];
    protected static $ignoredclasses       = [];
    protected static $ignoredinterfaces    = [];
    protected static $ignoredmethods       = [];
    protected static $ignoredconsts        = [];

    /**
     * Sets up the shared fixture.
     *
     * @return void
     * @link   http://phpunit.de/manual/current/en/fixtures.html#fixtures.sharing-fixture
     */
    public static function setUpBeforeClass(): void
    {
        $container = require implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 2), 'config', 'container.php']);

        self::$optionalreleases = [];

        $parts = explode('\\', get_called_class());
        $name = strtolower(
            str_replace('ExtensionTest', '', end($parts))
        );

        $factory = $container->get(ExtensionFactory::class);
        self::$obj = $factory->create($name);

        $currentVersion = phpversion($name);
        if ($currentVersion === false) {
            // extension did not provide any version information
            return;
        }

        $releases = self::$obj->getReleases();

        // platform dependant
        foreach ($releases as $release) {
            if (version_compare($currentVersion, $release->getVersion(), 'lt')) {
                array_push(self::$optionalreleases, $release->getVersion());
            }
        }
    }

    public static function tearDownAfterClass(): void
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
        self::$ignoredconsts      = [];
    }

    protected function setUp(): void
    {
        $name = self::$obj->getName();
        // special case(s)
        if ('opcache' === $name) {
            $name = 'zend opcache';
        }

        if (!extension_loaded($name)) {
            $this->markTestSkipped(
                sprintf('Extension %s is required.', $name)
            );
        }
    }

    /**
     * Generic Reference validator and producer
     *
     * @param array $elements
     * @param string $opt
     * @return Generator
     */
    private function provideReferenceValues(array $elements, string $opt): Generator
    {
        $extVersion = $this->getExtensionVersion(self::$obj->getName());

        foreach ($elements as $name => $element) {
            $range = [
                'ext.min' => $extVersion,
                'ext.max' => $element->getExtMax(),
                'php.min' => $element->getPhpMin(),
                'php.max' => $element->getPhpMax(),
            ];

            if (!empty($range['optional'])) {
                self::${$opt}[] = $name;
                continue;
            }

            foreach ($element->getDependencies() as $dependency) {
                /** @var Dependency $dependency */
                $ver = $this->getPrettyVersion($dependency->getName());
                if (!Semver::satisfies($ver, $dependency->getConstraint())) {
                    self::${$opt}[] = $name;
                    continue 2;
                }
            }
            yield [$name, $range];
        }
    }

    protected static function toText($number): string
    {
        $hex = dechex(($number & ~ 15) / 16);

        if (strlen($hex) % 2 !== 0) {
            $hex = '0' . $hex;
        }

        $arr = str_split($hex, 2);

        return implode('.', array_map('hexdec', $arr));
    }

    protected static function toNumber($text): int
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
     * @param string $element
     * @param array $range
     * @param array $optional
     * @param array $ignored
     * @return bool|null NULL if reference should be skipped, boolean otherwise
     */
    private function checkValuesFromReference(string $element, array $range, array $optional, array $ignored): ?bool
    {
        if (in_array($range['ext.min'], self::$optionalreleases)) {
            return null;
        }

        if (array_key_exists('php.excludes', $range)) {
            if (in_array(PHP_VERSION, $range['php.excludes'])) {
                // We are in min/max, so add it as optional
                array_push($optional, $element);
            }
        }

        if (in_array($element, $optional) || in_array($element, $ignored)) {
            return null;
        }

        $min = $range['php.min'];
        $max = $range['php.max'];

        $emin = $range['ext.min'];
        $emax = $range['ext.max'];

        $deprecated = $range['deprecated'] ?? '';

        if (!empty($deprecated)) {
            $shouldBeThere = version_compare(PHP_VERSION, $deprecated, 'le');

            // used also for elements that were moved from one extension to another;
            // i.e with `utf8_encode` (from `xml` to `standard` extension)

            if (!$shouldBeThere) {
                return null; // ignore it !
            }
        }

        $extVersion = $this->getExtensionVersion(self::$obj->getName());

        if (!empty($min)) {
            $shouldBeThere = version_compare(PHP_VERSION, $min, 'ge');
        } else {
            $shouldBeThere = false;
        }
        if (!empty($max) && $shouldBeThere) {
            $shouldBeThere = version_compare(PHP_VERSION, $max, 'le');
        }
        if (!empty($emin) && $shouldBeThere) {
            $shouldBeThere = version_compare($extVersion, $emin, 'ge');
        }
        if (!empty($emax) && $shouldBeThere) {
            $shouldBeThere = version_compare($extVersion, $emax, 'le');
        }

        // Should be there except if set as optional
        return $shouldBeThere;
    }

    /**
     * Provider to get INI entries from an extension
     *
     * @return Generator
     */
    private function iniEntriesFromExtensionProvider(): Generator
    {
        $extension = $this->getReflectionExtension();
        $elements  = array_keys($extension->getINIEntries());

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get constants from an extension
     *
     * @return Generator
     */
    private function constantsFromExtensionProvider(): Generator
    {
        $constants = get_defined_constants(true);

        if (defined('__PHPUNIT_PHAR__')) {
            // remove '' . "\0" . '__COMPILER_HALT_OFFSET__' . "\0" . __PHPUNIT_PHAR__
            array_pop($constants['Core']);
        }

        $ext = self::$obj->getName();

        $elements = isset($constants[$ext]) ? array_keys($constants[$ext]) : [];

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get functions from extension
     *
     * @return Generator
     */
    private function functionsFromExtensionProvider(): Generator
    {
        $ext = self::$obj->getName();

        $elements = get_extension_funcs(strtolower($ext));
        if (!is_array($elements)) {
            // can be NULL for ext without function
            $elements = [];
        }

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get classes from extension
     *
     * @return Generator
     */
    private function classesFromExtensionProvider(): Generator
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = array_filter($classes, 'class_exists');

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get interfaces from extension
     *
     * @return Generator
     */
    private function interfacesFromExtensionProvider(): Generator
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = array_filter($classes, 'interface_exists');

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get class constants from extension
     *
     * @return Generator
     */
    private function classConstantsFromExtensionProvider(): Generator
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = [];

        foreach ($classes as $classname) {
            try {
                $class = new ReflectionClass($classname);
            } catch (ReflectionException $e) {
                // abstract class
                continue;
            }
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

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get class methods from extension
     *
     * @return Generator
     */
    private function classMethodsFromExtensionProvider(): Generator
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = [];

        foreach ($classes as $classname) {
            try {
                $class = new ReflectionClass($classname);
            } catch (ReflectionException $e) {
                // abstract class
                continue;
            }
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
                } catch (ReflectionException $e) {
                    // none prototype for this method
                }

                $elements[] = $classname . '::' . $method->getName();
            }
        }

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Test than all referenced ini entries exists
     *
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromReference(): void
    {
        foreach($this->provideReferenceValues(self::$obj->getIniEntries(), 'optionalcfgs') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalcfgs,
                self::$ignoredcfgs
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    (false !== ini_get($element)),
                    "INI '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                $this->assertFalse(
                    (false !== ini_get($element)),
                    "INI '$element', found in Reference ($min, $max), exists."
                );
            }
        }
    }

    /**
     * Test that each ini entries are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromExtension(): void
    {
        $ext = self::$obj->getName();

        if ('internal' === $ext) {
            // only Core is a valid extension name for API reflection
            return;
        }

        $generator = $this->iniEntriesFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredcfgs)) {
            $this->assertExtensionComponentHasKey(
                $name,
                array_keys(self::$obj->getIniEntries()),
                "Defined INI '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced functions exists
     *
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromReference(): void
    {
        foreach ($this->provideReferenceValues(self::$obj->getFunctions(), 'optionalfunctions') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalfunctions,
                self::$ignoredfunctions
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                try {
                    $function = new ReflectionFunction($element);
                    $extensionName = $function->getExtensionName() ?: '';
                    if (strcasecmp($extensionName, self::$obj->getName()) !== 0) {
                        return;
                    }
                    $this->assertTrue(
                        $function->isInternal(),
                        "Function '$element', found in Reference, does not exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given function does not exist.
                    $this->assertTrue(
                        false,
                        "Function '$element', found in Reference, does not exists."
                    );
                }
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                try {
                    $function = new ReflectionFunction($element);
                    $extensionName = $function->getExtensionName() ?: '';
                    if (strcasecmp($extensionName, self::$obj->getName()) !== 0) {
                        return;
                    }
                    $this->assertFalse(
                        $function->isInternal(),
                        "Function '$element', found in Reference ($min, $max), exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given function does not exist.
                    return;
                }
            }
        }
    }

    /**
     * Test that each functions are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromExtension(): void
    {
        $generator = $this->functionsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredfunctions)) {
            $this->assertExtensionComponentHasKey(
                $name,
                array_keys(self::$obj->getFunctions()),
                "Defined function '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced constants exists
     *
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromReference(): void
    {
        foreach ($this->provideReferenceValues(self::$obj->getConstants(), 'optionalconstants') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalconstants,
                self::$ignoredconstants
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    defined($element),
                    "Constant '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                $this->assertFalse(
                    defined($element),
                    "Constant '$element', found in Reference ($min, $max), exists."
                );
            }
        }
    }

    /**
     * Test that each constants are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromExtension(): void
    {
        $generator = $this->constantsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredconstants)) {
            $this->assertExtensionComponentHasKey(
                $name,
                array_keys(self::$obj->getConstants()),
                "Defined constant '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced classes exists
     *
     * @group  reference
     * @return void
     */
    public function testGetClassesFromReference(): void
    {
        foreach ($this->provideReferenceValues(self::$obj->getClasses(), 'optionalclasses') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalclasses,
                self::$ignoredclasses
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    class_exists($element, false),
                    "Class '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                try {
                    $class = new ReflectionClass($element);
                    $extensionName = $class->getExtensionName() ?: '';
                    $this->assertFalse(
                        (strcasecmp($extensionName, self::$obj->getName()) === 0),
                        "Class '$element', found in Reference ($min, $max), exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given class does not exist.
                    return;
                }
            }
        }
    }

    /**
     * Test that each classes are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetClassesFromExtension(): void
    {
        $generator = $this->classesFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredclasses)) {
            $this->assertExtensionComponentHasKey(
                $name,
                array_keys(self::$obj->getClasses()),
                "Defined class '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced class methods exists
     *
     * @group  reference
     * @return void
     */
    public function testGetMethodsFromReference(): void
    {
        foreach ($this->provideReferenceValues(self::$obj->getMethods(), 'optionalmethods') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalmethods,
                self::$ignoredmethods
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            try {
                $method = new ReflectionMethod($element);
                $extensionName = $method->getExtensionName() ?: '';
                if (strcasecmp($extensionName, self::$obj->getName()) != 0) {
                    return;
                }
                try {
                    $method->getPrototype();
                    // don't check prototype methods
                    return;
                } catch (ReflectionException $e) {
                    // none prototype for this method
                }
                $methodExists = true;
            } catch (ReflectionException $e) {
                // thrown if the given method does not exist.
                $methodExists = false;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    $methodExists,
                    "Class Method '$element', found in Reference, does not exists."
                );
            } else {
                $this->assertFalse(
                    $methodExists,
                    "Class Method '$element', found in Reference, exists."
                );
            }
        }
    }

    /**
     * Test that each class methods are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetMethodsFromExtension(): void
    {
        $generator = $this->classMethodsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $methodName = $generator->current();
        if (in_array($methodName, self::$ignoredmethods)) {
            return;
        }

        $methods = self::$obj->getMethods();

        $this->assertNotEquals(
            0,
            count($methods),
            'None method defined. Checks if `methods.json` file exists.'
        );

        $this->assertContains(
            $methodName,
            array_keys($methods),
            "Defined method '$methodName' not known in Reference."
        );
    }

    /**
     * Test that each class constants are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetClassConstantsFromExtension(): void
    {
        $generator = $this->classConstantsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $constantName = $generator->current();

        if (!in_array($constantName, self::$ignoredconsts)) {
            $this->assertExtensionComponentHasKey(
                $constantName,
                array_keys(self::$obj->getClassConstants()),
                "Defined class constant '$constantName' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced interfaces exists
     *
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromReference(): void
    {
        foreach ($this->provideReferenceValues(self::$obj->getInterfaces(), 'optionalinterfaces') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalinterfaces,
                self::$ignoredinterfaces
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    interface_exists($element, false),
                    "Interface '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                try {
                    $class = new ReflectionClass($element);
                    if (!$class->isInterface()) {
                        return;
                    }
                    $extensionName = $class->getExtensionName() ?: '';
                    $this->assertFalse(
                        (strcasecmp($extensionName, self::$obj->getName()) === 0),
                        "Interface '$element', found in Reference ($min, $max), exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given interface does not exist.
                    return;
                }
            }
        }
    }

    /**
     * Test that each interface is defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromExtension(): void
    {
        $generator = $this->interfacesFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredinterfaces)) {
            $this->assertExtensionComponentHasKey(
                $name,
                array_keys(self::$obj->getInterfaces()),
                "Defined interface '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    private function assertExtensionComponentHasKey($needle, $haystack, $message, $obj): void
    {
        try {
            $this->assertContains($needle, $haystack, $message);
        } catch (ExpectationFailedException $e) {
            $warning = $this->checkUpdateExtension($obj);

            if (is_string($warning)) {
                $this->markTestSkipped($warning);
            } else {
                throw $e;
            }
        }
    }

    private function checkUpdateExtension(Extension $obj): ?string
    {
        $currentVersion = $this->getExtensionVersion($obj->getName());
        if ($currentVersion === false) {
            // extension did not provide any version information
            return null;
        }

        $latestReleaseReferenced = $obj->getLastRelease()->getVersion();
        // check if extension installed is more recent than the one declared in compatinfo-db
        if (version_compare($currentVersion, $latestReleaseReferenced, 'le')) {
            return null;
        }

        return sprintf(
            'Extension %s tested is version %s, while latest version referenced is %s and has need update.',
            $obj->getName(),
            $currentVersion,
            $latestReleaseReferenced
        );
    }

    private function getReflectionExtension(): ReflectionExtension
    {
        $name = self::$obj->getName();
        // special case(s)
        if ('opcache' === $name) {
            $name = 'zend opcache';
        }
        return new ReflectionExtension($name);
    }
}

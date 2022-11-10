<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Release;

use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;

use Generator;
use RuntimeException;
use function array_key_exists;
use function array_keys;
use function array_search;
use function current;
use function dirname;
use function end;
use function explode;
use function implode;
use function in_array;
use function json_last_error;
use function sprintf;
use function sscanf;
use const DIRECTORY_SEPARATOR;
use const JSON_ERROR_NONE;

/**
 * Handler to add a new PHP release in JSON files.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ReleaseHandler implements CommandHandlerInterface
{
    /** @var array<mixed, string>  */
    private array $latestPhpVersion = [
        '73' => ExtensionVersionProviderInterface::LATEST_PHP_7_3,
        '74' => ExtensionVersionProviderInterface::LATEST_PHP_7_4,
        '80' => ExtensionVersionProviderInterface::LATEST_PHP_8_0,
        '81' => ExtensionVersionProviderInterface::LATEST_PHP_8_1,
        '82' => ExtensionVersionProviderInterface::LATEST_PHP_8_2,
    ];
    private JsonFileHandler $jsonFileHandler;
    private string $refDir;

    /**
     * ReleaseHandler constructor.
     *
     * @param JsonFileHandler $jsonFileHandler
     */
    public function __construct(JsonFileHandler $jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
        $this->refDir = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 4), 'data', 'reference', 'extension']);
    }

    /**
     * @param ReleaseCommand $command
     */
    public function __invoke(ReleaseCommand $command): void
    {
        $relVersion = $command->getVersion();
        list($maj, $min, ) = sscanf($relVersion, '%d.%d.%s');

        $major = $maj . $min;

        if ($command->getExtension() == 'core') {
            $this->latestPhpVersion[$major] = $relVersion;
            $this->addNewRelease($major, $relVersion, $command->getDate(), $command->getState(), $command->getExtension());
            $this->tagPhpMaxVersion();
        } else {
            $this->addNewRelease((string) $maj, $relVersion, $command->getDate(), $command->getState(), $command->getExtension());
        }
    }

    /**
     * Adds a new release.
     */
    private function addNewRelease(string $major, string $relVersion, string $relDate, string $relState, string $refName): void
    {
        $fileBasename = 'releases';
        $path = implode(DIRECTORY_SEPARATOR, [$this->refDir, $refName]);

        $data = $this->jsonFileHandler->read($path, $fileBasename, $major);
        if (null === $data) {
            if (json_last_error() == JSON_ERROR_NONE) {
                $error = sprintf('File %s/%s.json does not exist.', $path, $fileBasename);
            } else {
                $error = sprintf('Cannot decode file %s/%s/%s.json', $path, $major, $fileBasename);
            }
            throw new RuntimeException($error);
        }

        end($data);
        $latestRelease = current($data);

        $newRelease = [
            'rel_version'   => $relVersion,
            'rel_date'      => $relDate,
            'rel_state'     => $relState,
            'php_min'       => $latestRelease['php_min'],
        ];
        if (false !== array_search($newRelease, $data)) {
            // already exists
            return;
        }
        $data[] = $newRelease;
        $this->jsonFileHandler->write($path, $fileBasename, $major, $data);
    }

    /**
     * Tags the latest PHP version added.
     *
     * @return void
     */
    private function tagPhpMaxVersion(): void
    {
        foreach ($this->componentDataProvider() as $refName => $definition) {
            if (count($definition) < 5) {
                list($fileBasename, $major, $entry, $names) = $definition;
                $static = null;
            } else {
                list($fileBasename, $major, $entry, $names, $static) = $definition;
            }

            $path = implode(DIRECTORY_SEPARATOR, [$this->refDir, $refName]);
            $data = $this->jsonFileHandler->read($path, $fileBasename, $major);
            if (null === $data) {
                if (json_last_error() == JSON_ERROR_NONE) {
                    $error = sprintf('File %s/%s/%s.json does not exist.', $path, $major, $fileBasename);
                } else {
                    $error = sprintf('Cannot decode file %s/%s/%s.json', $path, $major, $fileBasename);
                }
                throw new RuntimeException($error);
            }

            $key = $fileBasename == 'releases' ? 'rel_version' : 'name';

            $methods = [];
            if ('methods' === $fileBasename) {
                foreach (array_keys($names) as $method) {
                    $parts = explode('::', $method);
                    if (!isset($methods[$parts[0]])) {
                        $methods[$parts[0]] = [];
                    }
                    $methods[$parts[0]][] = $parts[1];
                }
            }

            foreach ($data as $index => $element) {
                if ('methods' === $fileBasename) {
                    if (array_key_exists($element['class_name'], $methods)) {
                        if (in_array($data[$index][$key], $methods[$element['class_name']])) {
                            if (isset($data[$index]['static']) && $data[$index]['static'] !== $static) {
                                continue;
                            }
                            $data[$index][$entry] = $names[implode('::', [$element['class_name'], $data[$index][$key]])];
                        }
                    }
                } else {
                    if (array_key_exists($data[$index][$key], $names)) {
                        $data[$index][$entry] = $names[$data[$index][$key]];
                    } elseif (array_key_exists('*', $names)) {
                        $data[$index][$entry] = $names['*'];
                    }
                }
            }
            $this->jsonFileHandler->write($path, $fileBasename, $major, $data);
        }
    }

    /**
     * Component data provider for tagging php Max version.
     *
     * @return Generator<string, mixed>
     */
    private function componentDataProvider(): Generator
    {
        $refName = 'core';
        $ext     = 'iniEntries';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'track_errors'                          => $this->latestPhpVersion['74'],
            'log_errors_max_len'                    => $this->latestPhpVersion['80'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'core';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'create_function'                       => $this->latestPhpVersion['74'],
            'each'                                  => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'dom';
        $ext     = 'classes';
        $major   = '50';
        $entry   = 'php_max';
        $names   = [
            'DOMConfiguration'                      => $this->latestPhpVersion['74'],
            'DOMDomError'                           => $this->latestPhpVersion['74'],
            'DOMErrorHandler'                       => $this->latestPhpVersion['74'],
            'DOMImplementationList'                 => $this->latestPhpVersion['74'],
            'DOMImplementationSource'               => $this->latestPhpVersion['74'],
            'DOMLocator'                            => $this->latestPhpVersion['74'],
            'DOMNameList'                           => $this->latestPhpVersion['74'],
            'DOMStringExtend'                       => $this->latestPhpVersion['74'],
            'DOMStringList'                         => $this->latestPhpVersion['74'],
            'DOMTypeinfo'                           => $this->latestPhpVersion['74'],
            'DOMUserDataHandler'                    => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'dom';
        $ext     = 'methods';
        $major   = '50';
        $entry   = 'php_max';
        $names   = [
            'DOMConfiguration::canSetParameter'     => $this->latestPhpVersion['74'],
            'DOMConfiguration::getParameter'        => $this->latestPhpVersion['74'],
            'DOMConfiguration::setParameter'        => $this->latestPhpVersion['74'],
            'DOMDocument::renameNode'               => $this->latestPhpVersion['74'],
            'DOMErrorHandler::handleError'          => $this->latestPhpVersion['74'],
            'DOMImplementationList::item'           => $this->latestPhpVersion['74'],
            'DOMImplementationSource::getDomimplementation'  => $this->latestPhpVersion['74'],
            'DOMImplementationSource::getDomimplementations' => $this->latestPhpVersion['74'],
            'DOMNameList::getName'                  => $this->latestPhpVersion['74'],
            'DOMNameList::getNamespaceURI'          => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::removeNamedItem'      => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::removeNamedItemNS'    => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::setNamedItem'         => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::setNamedItemNS'       => $this->latestPhpVersion['74'],
            'DOMNode::compareDocumentPosition'      => $this->latestPhpVersion['74'],
            'DOMNode::getFeature'                   => $this->latestPhpVersion['74'],
            'DOMNode::getUserData'                  => $this->latestPhpVersion['74'],
            'DOMNode::isEqualNode'                  => $this->latestPhpVersion['74'],
            'DOMNode::setUserData'                  => $this->latestPhpVersion['74'],
            'DOMStringExtend::findOffset16'         => $this->latestPhpVersion['74'],
            'DOMStringExtend::findOffset32'         => $this->latestPhpVersion['74'],
            'DOMStringList::item'                   => $this->latestPhpVersion['74'],
            'DOMText::replaceWholeText'             => $this->latestPhpVersion['74'],
            'DOMUserDataHandler::handle'            => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'exif';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'read_exif_data'                        => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'fileinfo';
        $ext     = 'methods';
        $major   = '0';
        $entry   = 'php_max';
        $names   = [
            'finfo::finfo'                          => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'filter';
        $ext     = 'constants';
        $major   = '0';
        $entry   = 'php_max';
        $names   = [
            'FILTER_FLAG_HOST_REQUIRED'             => $this->latestPhpVersion['74'],
            'FILTER_FLAG_SCHEME_REQUIRED'           => $this->latestPhpVersion['74'],
            'FILTER_SANITIZE_MAGIC_QUOTES'          => $this->latestPhpVersion['74'],
            'INPUT_REQUEST'                         => $this->latestPhpVersion['74'],
            'INPUT_SESSION'                         => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'gd';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'image2wbmp'                            => $this->latestPhpVersion['74'],
            'jpeg2wbmp'                             => $this->latestPhpVersion['74'],
            'png2wbmp'                              => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'gmp';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'gmp_random'                            => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'intl';
        $ext     = 'constants';
        $major   = '2';
        $entry   = 'php_max';
        $names   = [
            'INTL_IDNA_VARIANT_2003'                => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'ldap';
        $ext     = 'functions';
        $major   = '42';
        $entry   = 'php_max';
        $names   = [
            'ldap_sort'                             => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'ldap';
        $ext     = 'functions';
        $major   = '54';
        $entry   = 'php_max';
        $names   = [
            'ldap_control_paged_result'             => $this->latestPhpVersion['74'],
            'ldap_control_paged_result_response'    => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'mbstring';
        $ext     = 'iniEntries';
        $major   = '42';
        $entry   = 'php_max';
        $names   = [
            'mbstring.func_overload'                => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'mbstring';
        $ext     = 'functions';
        $major   = '42';
        $entry   = 'php_max';
        $names   = [
            'mbereg'                                => $this->latestPhpVersion['74'],
            'mbereg_match'                          => $this->latestPhpVersion['74'],
            'mbereg_replace'                        => $this->latestPhpVersion['74'],
            'mbereg_search'                         => $this->latestPhpVersion['74'],
            'mbereg_search_getpos'                  => $this->latestPhpVersion['74'],
            'mbereg_search_getregs'                 => $this->latestPhpVersion['74'],
            'mbereg_search_init'                    => $this->latestPhpVersion['74'],
            'mbereg_search_pos'                     => $this->latestPhpVersion['74'],
            'mbereg_search_regs'                    => $this->latestPhpVersion['74'],
            'mbereg_search_setpos'                  => $this->latestPhpVersion['74'],
            'mberegi'                               => $this->latestPhpVersion['74'],
            'mberegi_replace'                       => $this->latestPhpVersion['74'],
            'mbregex_encoding'                      => $this->latestPhpVersion['74'],
            'mbsplit'                               => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'mbstring';
        $ext     = 'constants';
        $major   = '40';
        $entry   = 'ext_max';
        $names   = [
            'MB_OVERLOAD_MAIL'                      => $this->latestPhpVersion['74'],
            'MB_OVERLOAD_REGEX'                     => $this->latestPhpVersion['74'],
            'MB_OVERLOAD_STRING'                    => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'memcached';
        $ext     = 'iniEntries';
        $major   = '2';
        $entry   = 'php_max';
        $names   = [
            'memcached.sess_binary'                 => $this->latestPhpVersion['74'],
            'memcached.sess_remove_failed'          => $this->latestPhpVersion['74'],
            'memcached.use_sasl'                    => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'mysqli';
        $ext     = 'iniEntries';
        $major   = '50';
        $entry   = 'php_max';
        $names   = [
            'mysqli.reconnect'                      => $this->latestPhpVersion['81'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'oci8';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = [
            'oci_internal_debug'                    => $this->latestPhpVersion['74'],
            'ociinternaldebug'                      => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'oci8';
        $ext     = 'classes';
        $major   = '1';
        $entry   = 'php_max';
        $names   = [
            'OCI-Collection'                        => $this->latestPhpVersion['74'],
            'OCI-Lob'                               => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'oci8';
        $ext     = 'methods';
        $major   = '1';
        $entry   = 'php_max';
        $names   = [
            'OCI-Collection::append'                => $this->latestPhpVersion['74'],
            'OCI-Collection::assign'                => $this->latestPhpVersion['74'],
            'OCI-Collection::assignelem'            => $this->latestPhpVersion['74'],
            'OCI-Collection::free'                  => $this->latestPhpVersion['74'],
            'OCI-Collection::getelem'               => $this->latestPhpVersion['74'],
            'OCI-Collection::max'                   => $this->latestPhpVersion['74'],
            'OCI-Collection::size'                  => $this->latestPhpVersion['74'],
            'OCI-Collection::trim'                  => $this->latestPhpVersion['74'],
            'OCI-Lob::append'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::close'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::eof'                          => $this->latestPhpVersion['74'],
            'OCI-Lob::erase'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::export'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::flush'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::free'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::getbuffering'                 => $this->latestPhpVersion['74'],
            'OCI-Lob::import'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::load'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::read'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::rewind'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::save'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::savefile'                     => $this->latestPhpVersion['74'],
            'OCI-Lob::seek'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::setbuffering'                 => $this->latestPhpVersion['74'],
            'OCI-Lob::size'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::tell'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::truncate'                     => $this->latestPhpVersion['74'],
            'OCI-Lob::write'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::writetemporary'               => $this->latestPhpVersion['74'],
            'OCI-Lob::writetofile'                  => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'reflection';
        $ext     = 'methods';
        $major   = '50';
        $entry   = 'php_max';
        $names   = [
            'Reflection::export'                    => $this->latestPhpVersion['74'],
            'ReflectionClass::export'               => $this->latestPhpVersion['74'],
            'ReflectionExtension::export'           => $this->latestPhpVersion['74'],
            'ReflectionFunction::export'            => $this->latestPhpVersion['74'],
            'ReflectionMethod::export'              => $this->latestPhpVersion['74'],
            'ReflectionParameter::export'           => $this->latestPhpVersion['74'],
            'ReflectionProperty::export'            => $this->latestPhpVersion['74'],
            'ReflectionZendExtension::export'       => $this->latestPhpVersion['74'],
            'Reflector::export'                     => $this->latestPhpVersion['74'],
        ];
        $static = true;
        yield $refName => [$ext, $major, $entry, $names, $static];

        $refName = 'reflection';
        $ext     = 'methods';
        $major   = '71';
        $entry   = 'php_max';
        $names   = [
            'ReflectionClassConstant::export'       => $this->latestPhpVersion['74'],
        ];
        $static = true;
        yield $refName => [$ext, $major, $entry, $names, $static];

        $refName = 'reflection';
        $ext     = 'methods';
        $major   = '70';
        $entry   = 'php_max';
        $names   = [
            'ReflectionType::isBuiltin'             => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'reflection';
        $ext     = 'methods';
        $major   = '71';
        $entry   = 'php_max';
        $names   = [
            'ReflectionClassConstant::export'       => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'soap';
        $ext     = 'methods';
        $major   = '50';
        $entry   = 'ext_max';
        $names   = [
            'SoapClient::SoapClient'                => $this->latestPhpVersion['74'],
            'SoapFault::SoapFault'                  => $this->latestPhpVersion['74'],
            'SoapHeader::SoapHeader'                => $this->latestPhpVersion['74'],
            'SoapParam::SoapParam'                  => $this->latestPhpVersion['74'],
            'SoapServer::SoapServer'                => $this->latestPhpVersion['74'],
            'SoapVar::SoapVar'                      => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'spl';
        $ext     = 'methods';
        $major   = '51';
        $entry   = 'ext_max';
        $names   = [
            'SplFileObject::fgetss'                 => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'standard';
        $ext     = 'iniEntries';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'assert.quiet_eval'                     => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'standard';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'convert_cyr_string'                    => $this->latestPhpVersion['74'],
            'ezmlm_hash'                            => $this->latestPhpVersion['74'],
            'fgetss'                                => $this->latestPhpVersion['74'],
            'get_magic_quotes_gpc'                  => $this->latestPhpVersion['74'],
            'get_magic_quotes_runtime'              => $this->latestPhpVersion['74'],
            'hebrevc'                               => $this->latestPhpVersion['74'],
            'is_real'                               => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'standard';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'ext_max';
        $names   = [
            'fgetss'                                => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'standard';
        $ext     = 'functions';
        $major   = '43';
        $entry   = 'php_max';
        $names   = [
            'money_format'                          => $this->latestPhpVersion['74'],
            'restore_include_path'                  => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'standard';
        $ext     = 'constants';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'ASSERT_QUIET_EVAL'                     => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'tidy';
        $ext     = 'methods';
        $major   = '0';
        $entry   = 'php_max';
        $names   = [
            // became static since PHP 8.0
            'tidy::repairFile'                      => $this->latestPhpVersion['74'],
            'tidy::repairString'                    => $this->latestPhpVersion['74'],
        ];
        $static = false;
        yield $refName => [$ext, $major, $entry, $names, $static];

        $refName = 'wddx';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'wddx_add_vars'                         => $this->latestPhpVersion['73'],
            'wddx_deserialize'                      => $this->latestPhpVersion['73'],
            'wddx_packet_end'                       => $this->latestPhpVersion['73'],
            'wddx_packet_start'                     => $this->latestPhpVersion['73'],
            'wddx_serialize_value'                  => $this->latestPhpVersion['73'],
            'wddx_serialize_vars'                   => $this->latestPhpVersion['73'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'xdebug';
        $ext     = 'iniEntries';
        $major   = '2';
        $entry   = 'php_max';
        $names   = [
            'xdebug.remote_cookie_expire_time'      => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'xdebug';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = [
            'xdebug_disable'                        => $this->latestPhpVersion['74'],
            'xdebug_enable'                         => $this->latestPhpVersion['74'],
            'xdebug_is_enabled'                     => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'xdebug';
        $ext     = 'functions';
        $major   = '2';
        $entry   = 'php_max';
        $names   = [
            'xdebug_get_declared_vars'              => $this->latestPhpVersion['74'],
            'xdebug_get_formatted_function_stack'   => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'xdebug';
        $ext     = 'constants';
        $major   = '2';
        $entry   = 'php_max';
        $names   = [
            'XDEBUG_NAMESPACE_BLACKLIST'            => $this->latestPhpVersion['74'],
            'XDEBUG_NAMESPACE_WHITELIST'            => $this->latestPhpVersion['74'],
            'XDEBUG_PATH_BLACKLIST'                 => $this->latestPhpVersion['74'],
            'XDEBUG_PATH_WHITELIST'                 => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_APPEND'                   => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_COMPUTERIZED'             => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_HTML'                     => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_NAKED_FILENAME'           => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'xmlreader';
        $ext     = 'methods';
        $major   = '50';
        $entry   = 'php_max';
        $names   = [
            // became static since PHP 8.0
            'XMLReader::XML'                        => $this->latestPhpVersion['74'],
            'XMLReader::open'                       => $this->latestPhpVersion['74'],
        ];
        $static = false;
        yield $refName => [$ext, $major, $entry, $names, $static];

        $refName = 'xmlrpc';
        $ext     = 'functions';
        $major   = '41';
        $entry   = 'php_max';
        $names   = [
            'xmlrpc_decode'                         => $this->latestPhpVersion['74'],
            'xmlrpc_decode_request'                 => $this->latestPhpVersion['74'],
            'xmlrpc_encode'                         => $this->latestPhpVersion['74'],
            'xmlrpc_encode_request'                 => $this->latestPhpVersion['74'],
            'xmlrpc_get_type'                       => $this->latestPhpVersion['74'],
            'xmlrpc_is_fault'                       => $this->latestPhpVersion['74'],
            'xmlrpc_parse_method_descriptions'      => $this->latestPhpVersion['74'],
            'xmlrpc_server_add_introspection_data'  => $this->latestPhpVersion['74'],
            'xmlrpc_server_call_method'             => $this->latestPhpVersion['74'],
            'xmlrpc_server_create'                  => $this->latestPhpVersion['74'],
            'xmlrpc_server_destroy'                 => $this->latestPhpVersion['74'],
            'xmlrpc_server_register_introspection_callback'
                                                    => $this->latestPhpVersion['74'],
            'xmlrpc_server_register_method'         => $this->latestPhpVersion['74'],
            'xmlrpc_set_type'                       => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];

        $refName = 'zlib';
        $ext     = 'functions';
        $major   = '40';
        $entry   = 'php_max';
        $names   = [
            'gzgetss'                               => $this->latestPhpVersion['74'],
        ];
        yield $refName => [$ext, $major, $entry, $names];
    }
}

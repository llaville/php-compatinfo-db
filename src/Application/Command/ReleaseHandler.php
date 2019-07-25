<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\ExtensionFactory;

class ReleaseHandler implements CommandHandlerInterface
{
    private $jsonFileHandler;

    public function __construct($jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
    }

    public function __invoke(ReleaseCommand $command): void
    {
        $latest  = array();

        $refName = 'Curl';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'CURLCLOSEPOLICY_CALLBACK'              => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_LEAST_RECENTLY_USED'   => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_LEAST_TRAFFIC'         => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_OLDEST'                => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_SLOWEST'               => ExtensionFactory::LATEST_PHP_5_5,
            'CURLOPT_CLOSEPOLICY'                   => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'allow_call_time_pass_reference'        => ExtensionFactory::LATEST_PHP_5_3,
            'define_syslog_variables'               => ExtensionFactory::LATEST_PHP_5_3,
            'highlight.bg'                          => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_gpc'                      => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_runtime'                  => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_sybase'                   => ExtensionFactory::LATEST_PHP_5_3,
            'register_globals'                      => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode'                             => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_exec_dir'                    => ExtensionFactory::LATEST_PHP_5_3,
            'y2k_compliance'                        => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_gid'                         => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_include_dir'                 => ExtensionFactory::LATEST_PHP_5_3,

            'always_populate_raw_post_data'         => ExtensionFactory::LATEST_PHP_5_6,
            'asp_tags'                              => ExtensionFactory::LATEST_PHP_5_6,

            'exit_on_timeout'                       => ExtensionFactory::LATEST_PHP_7_0,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'register_long_arrays'                  => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'constants';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'ZEND_MULTIBYTE'                        => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Fileinfo';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'FILEINFO_COMPRESS'                     => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '0.7.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            '1.0.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
            '1.3.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
            '1.5.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'HttpRequest'                           => ExtensionFactory::LATEST_PHP_5_5,
            'HttpResponse'                          => ExtensionFactory::LATEST_PHP_5_5,
            'HttpUtil'                              => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'HttpDeflateStream'                     => ExtensionFactory::LATEST_PHP_5_5,
            'HttpEncodingException'                 => ExtensionFactory::LATEST_PHP_5_5,
            'HttpException'                         => ExtensionFactory::LATEST_PHP_5_5,
            'HttpHeaderException'                   => ExtensionFactory::LATEST_PHP_5_5,
            'HttpInflateStream'                     => ExtensionFactory::LATEST_PHP_5_5,
            'HttpInvalidParamException'             => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMalformedHeadersException'         => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMessage'                           => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMessageTypeException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpQueryString'                       => ExtensionFactory::LATEST_PHP_5_5,
            'HttpQueryStringException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestException'                  => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestMethodException'            => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestPool'                       => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestPoolException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpResponseException'                 => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRuntimeException'                  => ExtensionFactory::LATEST_PHP_5_5,
            'HttpSocketException'                   => ExtensionFactory::LATEST_PHP_5_5,
            'HttpUrlException'                      => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestDataShare'                  => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Iconv';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_iconv_handler'                      => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'datefmt_set_timezone_id'               => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'methods';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            // IntlDateFormatter
            'setTimeZoneId'                         => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'MCRYPT_3DES'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ARCFOUR'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ARCFOUR_IV'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_BLOWFISH'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_CAST_128'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_CAST_256'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_CRYPT'                          => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DECRYPT'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DES'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DES_COMPAT'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DEV_RANDOM'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DEV_URANDOM'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ENCRYPT'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ENIGNA'                         => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_GOST'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_IDEA'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_LOKI97'                         => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MARS'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_CBC'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_CFB'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_ECB'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_NOFB'                      => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_OFB'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_STREAM'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_PANAMA'                         => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RAND'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC2'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC4'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6_128'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6_192'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6_256'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RIJNDAEL_128'                   => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RIJNDAEL_192'                   => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RIJNDAEL_256'                   => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SAFER128'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SAFER64'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SAFERPLUS'                      => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT_128'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT_192'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT_256'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SKIPJACK'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TEAN'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_THREEWAY'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TRIPLEDES'                      => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH128'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH192'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH256'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_WAKE'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_XTEA'                           => ExtensionFactory::LATEST_PHP_7_1,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'mcrypt_cbc'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_cfb'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_create_iv'                      => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_decrypt'                        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_ecb'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_algorithms_name'        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_block_size'             => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_iv_size'                => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_key_size'               => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_modes_name'             => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_supported_key_sizes'    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_is_block_algorithm'         => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_is_block_algorithm_mode'    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_is_block_mode'              => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_self_test'                  => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_encrypt'                        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic'                        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic_deinit'                 => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic_end'                    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic_init'                   => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_block_size'                 => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_cipher_name'                => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_iv_size'                    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_key_size'                   => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_list_algorithms'                => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_list_modes'                     => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_close'                   => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_get_algo_block_size'     => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_get_algo_key_size'       => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_get_supported_key_sizes' => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_is_block_algorithm'      => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_is_block_algorithm_mode' => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_is_block_mode'           => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_open'                    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_self_test'               => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_ofb'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mdecrypt_generic'                      => ExtensionFactory::LATEST_PHP_7_1,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'mcrypt.algorithms_dir'                 => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt.modes_dir'                      => ExtensionFactory::LATEST_PHP_7_1,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mcrypt_ecb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_cbc'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_cfb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_ofb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_generic_end'                    => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mysqli_bind_param'                     => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_bind_result'                    => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_client_encoding'                => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_disable_reads_from_master'      => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_disable_rpl_parse'              => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_enable_reads_from_master'       => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_enable_rpl_parse'               => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_fetch'                          => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_get_metadata'                   => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_master_query'                   => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_param_count'                    => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_rpl_parse_enabled'              => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_rpl_probe'                      => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_rpl_query_type'                 => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_send_long_data'                 => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_send_query'                     => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_slave_query'                    => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'MYSQLI_RPL_ADMIN'                      => ExtensionFactory::LATEST_PHP_5_2,
            'MYSQLI_RPL_MASTER'                     => ExtensionFactory::LATEST_PHP_5_2,
            'MYSQLI_RPL_SLAVE'                      => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oauth';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            // OAuth
            '__destruct'                            => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session_is_registered'                 => ExtensionFactory::LATEST_PHP_5_3,
            'session_register'                      => ExtensionFactory::LATEST_PHP_5_3,
            'session_unregister'                    => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session.entropy_file'                  => ExtensionFactory::LATEST_PHP_7_0,
            'session.entropy_length'                => ExtensionFactory::LATEST_PHP_7_0,
            'session.hash_function'                 => ExtensionFactory::LATEST_PHP_7_0,
            'session.hash_bits_per_character'       => ExtensionFactory::LATEST_PHP_7_0,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'interfaces';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'ArrayAccess'                           => ExtensionFactory::LATEST_PHP_5_2,
            'Iterator'                              => ExtensionFactory::LATEST_PHP_5_2,
            'IteratorAggregate'                     => ExtensionFactory::LATEST_PHP_5_2,
            'Serializable'                          => ExtensionFactory::LATEST_PHP_5_2,
            'Traversable'                           => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'SimpleXMLIterator'                     => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'safe_mode_allowed_env_vars'            => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_protected_env_vars'          => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'functions';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'define_syslog_variables'               => ExtensionFactory::LATEST_PHP_5_3,
            'php_logo_guid'                         => ExtensionFactory::LATEST_PHP_5_4,
            'php_real_logo_guid'                    => ExtensionFactory::LATEST_PHP_5_4,
            'zend_logo_guid'                        => ExtensionFactory::LATEST_PHP_5_4,
            'php_egg_logo_guid'                     => ExtensionFactory::LATEST_PHP_5_4,
            'import_request_variables'              => ExtensionFactory::LATEST_PHP_5_3,

            'call_user_method'                      => ExtensionFactory::LATEST_PHP_5_6,
            'call_user_method_array'                => ExtensionFactory::LATEST_PHP_5_6,
            'magic_quotes_runtime'                  => ExtensionFactory::LATEST_PHP_5_6,
            'set_magic_quotes_runtime'              => ExtensionFactory::LATEST_PHP_5_6,
            'set_socket_blocking'                   => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'constants';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'STREAM_ENFORCE_SAFE_MODE'              => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tidy';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_tidyhandler'                        => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tokenizer';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'T_BAD_CHARACTER'                       => ExtensionFactory::LATEST_PHP_5_6,
            'T_CHARACTER'                           => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xsl';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'xsl.security_prefs'                    => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Zendopcache';
        $ext     = 'iniEntries';
        $major   = '7';
        $entry   = 'php_max';
        $names   = array(
            'opcache.load_comments'                 => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        // tag MAX version
        while (!empty($latest)) {
            list($refName, $ext, $major, $entry, $names) = array_pop($latest);

            $data = $this->jsonFileHandler->read($refName, $ext, $major);

            if (!$data) {
                if (json_last_error() == JSON_ERROR_NONE) {
                    $error = sprintf('File %s.%s.json does not exist.', $refName, $ext);
                } else {
                    $error = sprintf('Cannot decode file %s.%s.json', $refName, $ext);
                }
                throw new \RuntimeException($error);
            }

            $key = $ext == 'releases' ? 'rel_version' : 'name';

            foreach ($data as &$element) {
                if (array_key_exists($element[$key], $names)) {
                    $element[$entry] = $names[$element[$key]];
                } elseif (array_key_exists('*', $names)) {
                    $element[$entry] = $names['*'];
                }
            }
            $this->jsonFileHandler->write($refName, $ext, $major, $data);
        }
    }
}

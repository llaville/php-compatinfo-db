<?php

$projectDir = dirname(__DIR__, 2);

$finder = (new PhpCsFixer\Finder())
    ->in([
        $projectDir . '/resources',
        $projectDir . '/src',
        $projectDir . '/tests',
    ])
;

return (new PhpCsFixer\Config())
    // allow to run on unsupported PHP Versions
    // {@link https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/README.md#supported-php-versions}
    // available since PHP-CS-Fixer 3.76.0
    // use instead `PHP_CS_FIXER_IGNORE_ENV` env var for older versions
    ->setUnsupportedPhpVersionAllowed(true)
    // use default @link https://www.php-fig.org/per/coding-style/
    ->registerCustomFixers(
        new PhpCsFixerCustomFixers\Fixers()
    )
    ->setRules([
        '@PHP8x3Migration' => true,
        'assign_null_coalescing_to_coalesce_equal' => false,
        'octal_notation' => false,
        'list_syntax' => false,
        'trailing_comma_in_multiline' => false,
        'no_whitespace_before_comma_in_array' => false,
        PhpCsFixerCustomFixers\Fixer\TypedClassConstantFixer::name() => true,
    ])
    // default source code to scan
    ->setFinder($finder)
;

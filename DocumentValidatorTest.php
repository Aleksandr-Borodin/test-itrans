<?php
/**
 * DocumentValidatorTest
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */


declare(strict_types=1);

/**
 * @return string
 */
function consoleResetColor(): string
{
    return "\033[0m";
}

/**
 * @return string
 */
function consoleGreenColor(): string
{
    return "\033[32m";
}

$tests = [
    'Rule/MaxDocumentSizeRuleTest',
    'Rule/ProhibitedWordsRuleTest',
    'Rule/RequiredMetadataFieldsRuleTest',
    'Provider/TenantRuleProviderTest',
    'Validator/DocumentValidatorTest',
];

foreach ($tests as $test) {
    echo PHP_EOL
        . consoleGreenColor()
        . "start unit tests from {$test}"
        . consoleResetColor()
        . PHP_EOL;
    passthru(
        __DIR__ . "/vendor/bin/phpunit tests/Unit/{$test}.php",
        $exitCode
    );
    if ($exitCode !== 0) {
        exit($exitCode);
    }
}

echo PHP_EOL
    . consoleGreenColor()
    . "All unit tests completed successfully."
    . consoleResetColor()
    . PHP_EOL;

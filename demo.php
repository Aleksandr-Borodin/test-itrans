<?php

declare(strict_types=1);
if (!in_array(PHP_SAPI, ['cli', 'cli-server', 'phpdbg'])) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use App\Model\Document;
use App\Validation\Provider\TenantRuleProvider;
use App\Validation\Rule\MaxDocumentSizeRule;
use App\Validation\Rule\ProhibitedWordsRule;
use App\Validation\Rule\RequiredMetadataFieldsRule;
use App\Validator\DocumentValidator;
use App\Validation\Result\ValidationResult;
use App\Config\EncodingConfig;

const DEFAULT_ENCODING = 'UTF-8';

/**
 * @return void
 */
function init(): void
{
    // add basic setup in future;
}

/**
 * @return void
 */
function main(): void
{
    $encodingConfig = createEncodingConfig();
    $tenantsConfig = buildDemoTenants($encodingConfig);
    $provider = createTenantRuleProvider($tenantsConfig);
    $validator = createValidator($provider);
    tenantsMain($tenantsConfig, $validator, $encodingConfig);
}

/**
 * @return EncodingConfig
 */
function createEncodingConfig(): EncodingConfig
{
    return (new EncodingConfig())->setEncoding(DEFAULT_ENCODING);
}

/**
 * @param array $tenantsConfig
 * @return TenantRuleProvider
 */
function createTenantRuleProvider(array $tenantsConfig): TenantRuleProvider
{
    $provider = new TenantRuleProvider();
    foreach ($tenantsConfig as $tenantConfig) {
        $provider->addRules(
            $tenantConfig['tenant_id'],
            $tenantConfig['rules']
        );
    }
    return $provider;
}

/**
 * @param array $tenantsConfig
 * @param DocumentValidator $validator
 * @param EncodingConfig $encodingConfig
 * @return void
 */
function tenantsMain(array $tenantsConfig, DocumentValidator $validator, EncodingConfig $encodingConfig): void
{
    foreach ($tenantsConfig as $tenantConfig) {
        tenantMain($tenantConfig, $validator, $encodingConfig);
    }
}

/**
 * Input data for test;
 * @param EncodingConfig $encodingConfig
 * @return array
 */
function buildDemoTenants(EncodingConfig $encodingConfig): array
{
    return [
        [
            'tenant_id' => 1,
            'document' => [
                'id' => 101,
                'content' => 'This document contains virus information.',
                'metadata' => [
                    'author' => 'Alex',
                    'category' => 'an apartment'
                ],
            ],
            'rules' => [
                new MaxDocumentSizeRule(1000),
                new RequiredMetadataFieldsRule([
                    'author',
                    'category',
                ]),
                new ProhibitedWordsRule([
                    'virus',
                    'malware',
                ],
                $encodingConfig)
            ],
        ],
        [
            'tenant_id' => 2,
            'document' => [
                'id' => 102,
                'content' => 'Top secret document.',
                'metadata' => [
                    'author' => 'John',
                ],
            ],
            'rules' => [
                new MaxDocumentSizeRule(50),
                new ProhibitedWordsRule([
                    'secret',
                    'classified',
                ],
                $encodingConfig)
            ],
        ],
        [
            'tenant_id' => 3,
            'document' => [
                'id' => 103,
                'content' => 'Regular document.',
                'metadata' => [
                    'author' => 'Mike',
                ],
            ],
            'rules' => [
                new MaxDocumentSizeRule(1500),
                new RequiredMetadataFieldsRule([
                    'author',
                ])
            ],
        ],
    ];
}

/**
 * @param array $tenantConfig
 * @param DocumentValidator $validator
 * @param EncodingConfig $encodingConfig
 * @return void
 */
function tenantMain(array $tenantConfig, DocumentValidator $validator, EncodingConfig $encodingConfig): void
{
    $document = createDocument($tenantConfig, $encodingConfig);
    $result = $validator->validate($document);
    printResult($result, $document);
}

/**
 * @param TenantRuleProvider $provider
 * @return DocumentValidator
 */
function createValidator(TenantRuleProvider $provider): DocumentValidator
{
    return new DocumentValidator($provider);
}

/**
 * @param array $tenantConfig
 * @param EncodingConfig $encodingConfig
 * @return Document
 */
function createDocument(array $tenantConfig, EncodingConfig $encodingConfig): Document
{
    $documentConfig = $tenantConfig['document'];
    return (new Document($encodingConfig))
        ->setId($documentConfig['id'])
        ->setTenantId($tenantConfig['tenant_id'])
        ->setContent($documentConfig['content'])
        ->setMetadata($documentConfig['metadata']);
}

/**
 * @param ValidationResult $result
 * @param Document $document
 * @return void
 */
function printResult(ValidationResult $result, Document $document): void
{
    echo str_repeat('-', 40) . PHP_EOL;
    echo "Tenant:   {$document->getTenantId()}" . PHP_EOL;
    echo "Document: {$document->getId()}" . PHP_EOL;
    echo "Content: {$document->getContent()}" . PHP_EOL;
    echo "Metadata: " . json_encode(
        $document->getMetadata(),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    ) . PHP_EOL;
    if ($result->isValid()) {
        echo colorText(
            "Status: VALID",
            consoleGreenColor()
        ) . PHP_EOL . PHP_EOL;

        return;
    }
    echo colorText(
        "Status: FAILED",
        consoleRedColor()
    ) . PHP_EOL . PHP_EOL;
    foreach ($result->getErrors() as $error) {
        echo "- {$error}" . PHP_EOL;
    }
    echo PHP_EOL;
}

/**
 * @param string $text
 * @param string $color
 * @return string
 */
function colorText(string $text, string $color): string
{
    return $color . $text . consoleResetColor();
}

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

/**
 * @return string
 */
function consoleRedColor(): string
{
    return "\033[31m";
}

/**
 * Entry point
 * @return void
 */
function run(): void
{
    try {
        init();
        main();
    } catch (\Error $e) {
        // scenario for Error;
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
        return;
    } catch (\InvalidArgumentException $e) {
        // scenario for InvalidArgumentException;
        echo 'InvalidArgumentException: ' . $e->getMessage() . PHP_EOL;
        return;
    } catch (\Exception $e) {
        // scenario for Exception;
        echo 'Exception: ' . $e->getMessage() . PHP_EOL;
        return;
    }
}

run();

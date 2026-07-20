<?php
/**
 * DocumentValidatorTest
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace Tests\Unit\Validator;

use App\Model\Document;
use App\Validation\Provider\RuleProviderInterface;
use App\Validation\Rule\ValidationRuleInterface;
use App\Validator\DocumentValidator;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\DocumentFixture;

final class DocumentValidatorTest extends TestCase
{
    /**
     * @param array $errors
     * @return ValidationRuleInterface
     */
    private function createRule(array $errors): ValidationRuleInterface
    {
        return new class($errors) implements ValidationRuleInterface {

            public function __construct(
                private readonly array $errors
            ) {
            }

            public function validate(Document $document): array
            {
                return $this->errors;
            }
        };
    }

    /**
     * @param array $rules
     * @return RuleProviderInterface
     */
    private function createRuleProvider(array $rules): RuleProviderInterface
    {
        return new class($rules) implements RuleProviderInterface {

            public function __construct(
                private readonly array $rules
            ) {
            }

            public function getRules(int $tenantId): array
            {
                return $this->rules;
            }
        };
    }

    /**
     * @return void
     */
    public function testValidDocumentReturnsEmptyValidationResult(): void
    {
        $provider = $this->createRuleProvider(
            [
                $this->createRule([]),
            ]
        );
        $validator = new DocumentValidator($provider);
        $document = DocumentFixture::createValidDocument();
        $result = $validator->validate($document);
        self::assertEmpty(
            $result->getErrors()
        );
    }

    /**
     * @return void
     */
    public function testDocumentWithValidationErrorsReturnsErrors(): void
    {
        $provider = $this->createRuleProvider(
            [
                $this->createRule(
                    [
                        'First validation error.',
                    ]
                ),
            ]
        );
        $validator = new DocumentValidator($provider);
        $document = DocumentFixture::createValidDocument();
        $result = $validator->validate($document);
        self::assertCount(
            1,
            $result->getErrors()
        );
        self::assertSame(
            [
                'First validation error.',
            ],
            $result->getErrors()
        );
    }

    /**
     * @return void
     */
    public function testErrorsFromMultipleRulesAreMerged(): void
    {
        $provider = $this->createRuleProvider(
            [
                $this->createRule(
                    [
                        'First error.',
                    ]
                ),
                $this->createRule(
                    [
                        'Second error.',
                        'Third error.',
                    ]
                ),
            ]
        );
        $validator = new DocumentValidator($provider);
        $document = DocumentFixture::createValidDocument();
        $result = $validator->validate($document);
        self::assertSame(
            [
                'First error.',
                'Second error.',
                'Third error.',
            ],
            $result->getErrors()
        );
    }

    /**
     * @return void
     */
    public function testDocumentWithoutRulesReturnsEmptyResult(): void
    {
        $provider = $this->createRuleProvider([]);
        $validator = new DocumentValidator($provider);
        $document = DocumentFixture::createValidDocument();
        $result = $validator->validate($document);
        self::assertEmpty(
            $result->getErrors()
        );
    }

    /**
     * @return void
     */
    public function testValidatorUsesDocumentTenantId(): void
    {
        $provider = new class implements RuleProviderInterface {
            public int $requestedTenantId = 0;
            public function getRules(int $tenantId): array
            {
                $this->requestedTenantId = $tenantId;
                return [];
            }
        };
        $validator = new DocumentValidator($provider);
        $document = DocumentFixture::createValidDocument();
        $validator->validate($document);
        self::assertSame(
            1,
            $provider->requestedTenantId
        );
    }
}
<?php
/**
 * RequiredMetadataFieldsRuleTest
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace Tests\Unit\Rule;

use App\Validation\Rule\RequiredMetadataFieldsRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\DocumentFixture;

final class RequiredMetadataFieldsRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testDocumentWithRequiredMetadataFieldsIsValid(): void
    {
        $rule = new RequiredMetadataFieldsRule(
            [
                'author',
                'category',
            ]
        );
        $document = DocumentFixture::createValidDocument();
        $errors = $rule->validate($document);
        self::assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testMissingRequiredMetadataFieldReturnsError(): void
    {
        $rule = new RequiredMetadataFieldsRule(
            [
                'author',
                'category',
            ]
        );
        $document = DocumentFixture::createDocumentWithoutRequiredMetadata();
        $errors = $rule->validate($document);
        self::assertCount(1, $errors);
        self::assertSame(
            'Required metadata field category is missing.',
            $errors[0]
        );
    }

    /**
     * @return void
     */
    public function testMultipleMissingMetadataFieldsReturnMultipleErrors(): void
    {
        $document = DocumentFixture::createValidDocument()
            ->setMetadata([]);
        $rule = new RequiredMetadataFieldsRule(
            [
                'author',
                'category',
            ]
        );
        $errors = $rule->validate($document);
        self::assertCount(2, $errors);
        self::assertSame(
            [
                'Required metadata field author is missing.',
                'Required metadata field category is missing.',
            ],
            $errors
        );
    }

    /**
     * @return void
     */
    public function testExistingMetadataFieldIsAccepted(): void
    {
        $document = DocumentFixture::createValidDocument()
            ->setMetadata([
                'author' => 'Alex',
                'category' => null,
            ]);
        $rule = new RequiredMetadataFieldsRule(
            [
                'author',
                'category',
            ]
        );
        $errors = $rule->validate($document);
        self::assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testEmptyRequiredFieldsArrayThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RequiredMetadataFieldsRule([]);
    }

    /**
     * @return void
     */
    public function testNonStringRequiredFieldThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RequiredMetadataFieldsRule(
            [
                'author',
                123,
            ]
        );
    }
}

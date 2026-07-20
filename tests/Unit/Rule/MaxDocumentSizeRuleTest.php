<?php
/**
 * MaxDocumentSizeRuleTest
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace Tests\Unit\Rule;

use App\Validation\Rule\MaxDocumentSizeRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\DocumentFixture;

final class MaxDocumentSizeRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testValidDocumentDoesNotExceedMaximumSize(): void
    {
        $rule = new MaxDocumentSizeRule(100);
        $document = DocumentFixture::createValidDocument();
        $errors = $rule->validate($document);
        self::assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testDocumentWithExactMaximumSizeIsValid(): void
    {
        $rule = new MaxDocumentSizeRule(2000);
        $document = DocumentFixture::createLargeDocument(2000);
        $errors = $rule->validate($document);
        self::assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testLargeDocumentExceedsMaximumSize(): void
    {
        $rule = new MaxDocumentSizeRule(1000);
        $document = DocumentFixture::createLargeDocument(2000);
        $errors = $rule->validate($document);
        self::assertCount(1, $errors);
        self::assertSame(
            'Document size exceeds maximum allowed size of 1000 characters.',
            $errors[0]
        );
    }

    /**
     * @return void
     */
    public function testNegativeMaximumSizeIsNotAllowed(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MaxDocumentSizeRule(-1);
    }
}

<?php
/**
 * ProhibitedWordsRuleTest
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace Tests\Unit\Rule;

use App\Config\EncodingConfig;
use App\Validation\Rule\ProhibitedWordsRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\DocumentFixture;

final class ProhibitedWordsRuleTest extends TestCase
{
    /**
     * @return EncodingConfig
     */
    private function createEncodingConfig(): EncodingConfig
    {
        return (new EncodingConfig())
            ->setEncoding('UTF-8');
    }

    /**
     * @return void
     */
    public function testDocumentWithoutProhibitedWordsIsValid(): void
    {
        $rule = new ProhibitedWordsRule(
            ['virus', 'malware'],
            $this->createEncodingConfig()
        );
        $document = DocumentFixture::createValidDocument();
        $errors = $rule->validate($document);
        self::assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testDocumentContainingProhibitedWordReturnsError(): void
    {
        $rule = new ProhibitedWordsRule(
            ['virus'],
            $this->createEncodingConfig()
        );
        $document = DocumentFixture::createDocumentWithProhibitedWord();
        $errors = $rule->validate($document);
        self::assertCount(1, $errors);
        self::assertSame(
            'Document contains prohibited word virus.',
            $errors[0]
        );
    }

    /**
     * @return void
     */
    public function testProhibitedWordSearchIsCaseInsensitive(): void
    {
        $document = DocumentFixture::createValidDocument()
            ->setContent('This document contains VIRUS information.');
        $rule = new ProhibitedWordsRule(
            ['virus'],
            $this->createEncodingConfig()
        );
        $errors = $rule->validate($document);
        self::assertCount(1, $errors);
        self::assertSame(
            'Document contains prohibited word virus.',
            $errors[0]
        );
    }

    /**
     * @return void
     */
    public function testMultipleProhibitedWordsReturnMultipleErrors(): void
    {
        $document = DocumentFixture::createValidDocument()
            ->setContent(
                'This document contains virus and malware information.'
            );
        $rule = new ProhibitedWordsRule(
            ['virus', 'malware'],
            $this->createEncodingConfig()
        );
        $errors = $rule->validate($document);
        self::assertCount(2, $errors);
        self::assertSame(
            [
                'Document contains prohibited word virus.',
                'Document contains prohibited word malware.',
            ],
            $errors
        );
    }

    /**
     * @return void
     */
    public function testEmptyProhibitedWordsArrayThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ProhibitedWordsRule(
            [],
            $this->createEncodingConfig()
        );
    }

    /**
     * @return void
     */
    public function testNonStringProhibitedWordThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ProhibitedWordsRule(
            ['virus', 123],
            $this->createEncodingConfig()
        );
    }
}

<?php
/**
 * DocumentFixture
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Config\EncodingConfig;
use App\Model\Document;

final class DocumentFixture
{
    /**
     * @return Document
     */
    public static function createValidDocument(): Document
    {
        return (new Document(self::createEncodingConfig()))
            ->setId(1)
            ->setTenantId(1)
            ->setContent('Regular document content.')
            ->setMetadata([
                'author' => 'Alex',
                'category' => 'test',
            ]);
    }

    /**
     * @return Document
     */
    public static function createDocumentWithProhibitedWord(): Document
    {
        return (new Document(self::createEncodingConfig()))
            ->setId(2)
            ->setTenantId(1)
            ->setContent('This document contains virus information.')
            ->setMetadata([
                'author' => 'Alex',
                'category' => 'test',
            ]);
    }

    /**
     * @return Document
     */
    public static function createDocumentWithoutRequiredMetadata(): Document
    {
        return (new Document(self::createEncodingConfig()))
            ->setId(3)
            ->setTenantId(1)
            ->setContent('Regular document content.')
            ->setMetadata([
                'author' => 'Alex',
            ]);
    }

    /**
     * @param int $size
     * @return Document
     */
    public static function createLargeDocument(int $size = 2000): Document
    {
        return (new Document(self::createEncodingConfig()))
            ->setId(4)
            ->setTenantId(1)
            ->setContent(str_repeat('a', $size))
            ->setMetadata([
                'author' => 'Alex',
                'category' => 'test',
            ]);
    }

    /**
     * @return EncodingConfig
     */
    private static function createEncodingConfig(): EncodingConfig
    {
        return (new EncodingConfig())
            ->setEncoding('UTF-8');
    }
}

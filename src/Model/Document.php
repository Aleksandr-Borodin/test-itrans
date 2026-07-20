<?php
/**
 * Document.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Model;

final class Document
{
    /**
     * @var int
     */
    protected int $_id;

    /**
     * @var int
     */
    protected int $_tenantId;

    /**
     * @var string
     */
    protected string $_content = '';

    /**
     * @var array
     */
    protected array $_metadata = [];

    /**
     * Encoding used for multibyte string operations.
     */
    private const CONTENT_ENCODING = 'UTF-8';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTenantId(): int
    {
        return $this->_tenantId;
    }

    /**
     * @param int $tenantId
     * @return self
     */
    public function setTenantId(int $tenantId): self
    {
        $this->_tenantId = $tenantId;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->_content;
    }

    /**
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->_content = $content;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->_metadata;
    }

    /**
     * @param array $metadata
     * @return self
     */
    public function setMetadata(array $metadata): self
    {
        $this->_metadata = $metadata;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function addMetadata(string $key, mixed $value): self
    {
        $this->_metadata[$key] = $value;
        return $this;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function hasMetadataField(string $field): bool
    {
        return array_key_exists($field, $this->_metadata);
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function getMetadataValue(string $field): mixed
    {
        if (!$this->hasMetadataField($field)) {
            return null;
        }
        return $this->_metadata[$field];
    }

    /**
     * @return int
     */
    public function getContentSize(): int
    {
        return mb_strlen($this->_content, self::CONTENT_ENCODING);
    }
}

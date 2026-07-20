<?php
/**
 * MaxDocumentSizeRule.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Model\Document;
use InvalidArgumentException;

final class MaxDocumentSizeRule implements ValidationRuleInterface
{
    /**
     * @var int
     */
    protected readonly int $_maxSize;

    /**
     * @param int $maxSize
     */
    public function __construct(int $maxSize)
    {
        $this->_validateMaxSize($maxSize);
        $this->_maxSize = $maxSize;
    }

    /**
     * @param int $maxSize
     * @return void
     * @throws InvalidArgumentException
     */
    protected function _validateMaxSize(int $maxSize): void
    {
        if ($maxSize >= 0) { return; }
        throw new InvalidArgumentException(
            'Maximum document size cannot be negative.'
        );
    }

    /**
     * @param Document $document
     *
     * @return string[]
     */
    public function validate(Document $document): array
    {
        $errors = [];
        if ($document->getContentSize() > $this->_maxSize) {
            $errors[] = $this->_getErrorMessage();
        }
        return $errors;
    }

    /**
     * @return string
     */
    protected function _getErrorMessage(): string
    {
        return "Document size exceeds maximum allowed size of {$this->_maxSize} characters.";
    }
}

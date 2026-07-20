<?php
/**
 * RequiredMetadataFieldsRule.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Model\Document;
use App\Validation\Rule\Trait\StringArrayValidatorTrait;

final class RequiredMetadataFieldsRule implements ValidationRuleInterface
{
    use StringArrayValidatorTrait;
    
    /**
     * @var string[]
     */
    protected readonly array $_requiredFields;

    /**
     * @param string[] $requiredFields
     */
    public function __construct(array $requiredFields)
    {
        $this->_validateStringArray(
            $requiredFields,
            'Required metadata fields'
        );
        $this->_requiredFields = $requiredFields;
    }

    /**
     * @param Document $document
     * @return array
     */
    public function validate(Document $document): array
    {
        $errors = [];
        foreach ($this->_requiredFields as $fieldName) {
            if ($document->hasMetadataField($fieldName)) { continue; }
            $errors[] = $this->_getErrorMessage($fieldName);
        }
        return $errors;
    }

    /**
     * @param string $fieldName
     * @return string
     */
    protected function _getErrorMessage(string $fieldName): string
    {
        return "Required metadata field {$fieldName} is missing.";
    }
}

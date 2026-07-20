<?php
/**
 * DocumentValidator.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validator;

use App\Model\Document;
use App\Validation\Provider\RuleProviderInterface;
use App\Validation\Result\ValidationResult;

final class DocumentValidator
{
    /**
     * @var RuleProviderInterface
     */
    protected readonly RuleProviderInterface $_ruleProvider;

    /**
     * @param RuleProviderInterface $ruleProvider
     */
    public function __construct(RuleProviderInterface $ruleProvider)
    {
        $this->_ruleProvider = $ruleProvider;
    }

    /**
     * @param Document $document
     * @return ValidationResult
     */
    public function validate(Document $document): ValidationResult
    {
        $errors = [];
        $rules = $this->_ruleProvider->getRules(
            $document->getTenantId()
        );
        foreach ($rules as $rule) {
            $errors = array_merge(
                $errors,
                $rule->validate($document)
            );
        }
        return new ValidationResult($errors);
    }
}

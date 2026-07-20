<?php
/**
 * ValidationResult.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validation\Result;

final class ValidationResult
{
    /**
     * @var bool
     */
    protected readonly bool $_isValid;

    /**
     * @var string[]
     */
    protected readonly array $_errors;

    /**
     * @param string[] $errors
     */
    public function __construct(array $errors = [])
    {
        $this->_errors = $errors;
        $this->_isValid = count($errors) === 0;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->_isValid;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }
}

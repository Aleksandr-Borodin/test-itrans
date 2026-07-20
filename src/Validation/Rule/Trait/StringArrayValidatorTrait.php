<?php

declare(strict_types=1);

namespace App\Validation\Rule\Trait;

use InvalidArgumentException;

trait StringArrayValidatorTrait
{
    /**
     * @param array $values
     * @param string $fieldName
     * @return void
     * @throws InvalidArgumentException
     */
    protected function _validateStringArray(array $values, string $fieldName): void
    {
        if ($values === []) {
            throw new InvalidArgumentException(
                "{$fieldName} list cannot be empty."
            );
        }
        foreach ($values as $value) {
            if (is_string($value) && $value !== '') {
                continue;
            }
            throw new InvalidArgumentException(
                "{$fieldName} value must be a non-empty string."
            );
        }
    }
}

<?php
/**
 * TenantRuleProvider.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validation\Provider;

use App\Validation\Rule\ValidationRuleInterface;
use InvalidArgumentException;

final class TenantRuleProvider
{
    /**
     * @var array<int, ValidationRuleInterface[]>
     */
    protected array $_tenantRules = [];

    /**
     * @param int $tenantId
     * @param array $rules
     * @return self
     * @throws InvalidArgumentException
     */
    public function addRules(int $tenantId, array $rules): self
    {
        foreach ($rules as $rule) {
            if ($rule instanceof ValidationRuleInterface) {
                continue;
            }
            throw new InvalidArgumentException(
                'Tenant rule must implement ValidationRuleInterface.'
            );
        }
        $this->_tenantRules[$tenantId] = $rules;
        return $this;
    }

    /**
     * @param int $tenantId
     * @return array
     */
    public function getRules(int $tenantId): array
    {
        return $this->_tenantRules[$tenantId] ?? [];
    }
}

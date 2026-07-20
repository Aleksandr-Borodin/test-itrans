<?php

declare(strict_types=1);

namespace App\Validation\Provider;

interface RuleProviderInterface
{
    public function getRules(int $tenantId): array;
}

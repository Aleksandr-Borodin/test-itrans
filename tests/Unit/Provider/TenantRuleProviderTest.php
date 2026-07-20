<?php
/**
 * TenantRuleProviderTest
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace Tests\Unit\Provider;

use App\Model\Document;
use App\Validation\Provider\TenantRuleProvider;
use App\Validation\Rule\ValidationRuleInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TenantRuleProviderTest extends TestCase
{
    /**
     * @return ValidationRuleInterface
     */
    private function createRule(): ValidationRuleInterface
    {
        return new class implements ValidationRuleInterface {
            public function validate(Document $document): array
            {
                return [];
            }
        };
    }

    /**
     * @return void
     */
    public function testRulesCanBeAddedAndRetrievedByTenantId(): void
    {
        $provider = new TenantRuleProvider();
        $rule = $this->createRule();
        $provider->addRules(
            1,
            [
                $rule,
            ]
        );
        $rules = $provider->getRules(1);
        self::assertCount(1, $rules);
        self::assertSame($rule, $rules[0]);
    }

    /**
     * @return void
     */
    public function testUnknownTenantReturnsEmptyRulesArray(): void
    {
        $provider = new TenantRuleProvider();
        $rules = $provider->getRules(999);
        self::assertEmpty($rules);
    }

    /**
     * @return void
     */
    public function testAddRulesReturnsSameProviderInstance(): void
    {
        $provider = new TenantRuleProvider();
        $result = $provider->addRules(
            1,
            [
                $this->createRule(),
            ]
        );
        self::assertSame($provider, $result);
    }

    /**
     * @return void
     */
    public function testAddingInvalidRuleThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $provider = new TenantRuleProvider();
        $provider->addRules(
            1,
            [
                new \stdClass(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testAddingEmptyRulesArrayIsAllowed(): void
    {
        $provider = new TenantRuleProvider();
        $provider->addRules(1, []);
        self::assertSame(
            [],
            $provider->getRules(1)
        );
    }
}

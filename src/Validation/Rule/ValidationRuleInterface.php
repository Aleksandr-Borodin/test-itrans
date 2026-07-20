<?php
/**
 * ValidationRuleInterface.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Model\Document;

interface ValidationRuleInterface
{
    /**
     * @param Document $document
     * @return array
     */
    public function validate(Document $document): array;
}

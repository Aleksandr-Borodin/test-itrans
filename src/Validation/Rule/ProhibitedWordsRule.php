<?php
/**
 * ProhibitedWordsRule.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Model\Document;
use App\Config\EncodingConfig;
use App\Validation\Rule\Trait\StringArrayValidatorTrait;

final class ProhibitedWordsRule implements ValidationRuleInterface
{
    use StringArrayValidatorTrait;
    
    /**
     * @var string[]
     */
    protected readonly array $_prohibitedWords;

    /**
     * @var EncodingConfig
     */
    protected readonly EncodingConfig $_encodingConfig;
    
    /**
     * @param string[] $prohibitedWords
     * @param EncodingConfig $encodingConfig
     * @throws InvalidArgumentException
     */
    public function __construct(array $prohibitedWords, EncodingConfig $encodingConfig)
    {
        $this->_validateStringArray(
            $prohibitedWords,
            'Prohibited metadata words'
        );
        $this->_prohibitedWords = $prohibitedWords;
        $this->_encodingConfig = $encodingConfig;
    }

    /**
     * @param Document $document
     * @return array
     */
    public function validate(Document $document): array
    {
        $errors = [];
        foreach ($this->_prohibitedWords as $word) {
            if (!$this->_containsWord($document->getContent(), $word)) {
                continue;
            }
            $errors[] = $this->_getErrorMessage($word);
        }
        return $errors;
    }

    /**
     * @param string $content
     * @param string $word
     * @return bool
     */
    protected function _containsWord(string $content, string $word): bool
    {
        return mb_stripos($content, $word, 0, $this->_encodingConfig->getEncoding()) !== false;
    }

    /**
     * @param string $word
     * @return string
     */
    protected function _getErrorMessage(string $word): string
    {
        return "Document contains prohibited word {$word}.";
    }
}

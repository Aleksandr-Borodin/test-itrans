<?php
/**
 * EncodingConfig.php
 * Created: 20.07.2026
 * Author: Alex
 * Project: test-itrans
 */

declare(strict_types=1);

namespace App\Config;

use InvalidArgumentException;

final class EncodingConfig
{
    /**
     * @var string
     */
    protected string $_encoding = '';

    /**
     * @param string $encoding
     *
     * @return self
     */
    public function setEncoding(string $encoding): self
    {
        if (!$encoding) {
            throw new InvalidArgumentException(
                'Encoding cannot be empty.'
            );
        }
        $this->_encoding = $encoding;
        return $this;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->_encoding;
    }
}

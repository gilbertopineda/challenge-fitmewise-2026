<?php

namespace App\Engagement\Domain\ValueObjects;

class Quote
{
    public function __construct(
        public readonly string $text,
        public readonly string $author
    )
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->text)) {
            throw new \InvalidArgumentException('Quote text cannot be empty');
        }

        if (empty($this->author)) {
            throw new \InvalidArgumentException('Quote author cannot be empty');
        }
    }
}

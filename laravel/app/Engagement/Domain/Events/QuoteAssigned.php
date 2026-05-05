<?php

namespace App\Engagement\Domain\Events;

class QuoteAssigned
{
    public function __construct(
        public string $checkInId,
        public string $userId,
        public string $quote,
        public string $author,
        public string $occurredAt
    )
    {
    }
}

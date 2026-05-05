<?php

namespace App\AccessControl\Domain\Events;

class UserCheckedIn
{
    public function __construct(
        public string $checkInId,
        public string $userId,
        public string $occurredAt
    )
    {
    }
}

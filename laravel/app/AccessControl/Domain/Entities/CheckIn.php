<?php

namespace App\AccessControl\Domain\Entities;

class CheckIn
{
    public function __construct(
        public string             $id,
        public string             $userId,
        public \DateTimeImmutable $checkedInAt
    )
    {
    }
}

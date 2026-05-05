<?php

namespace App\AccessControl\Application\Commands;

class CheckInUserCommand
{
    public function __construct(
        public string $userId
    )
    {
    }
}

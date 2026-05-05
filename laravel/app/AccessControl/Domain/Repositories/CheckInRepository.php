<?php

namespace App\AccessControl\Domain\Repositories;

use App\AccessControl\Domain\Entities\CheckIn;

interface CheckInRepository
{
    public function save(CheckIn $checkIn): void;
}

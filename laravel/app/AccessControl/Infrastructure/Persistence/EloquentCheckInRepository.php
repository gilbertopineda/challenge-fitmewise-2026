<?php

namespace App\AccessControl\Infrastructure\Persistence;

use App\AccessControl\Domain\Repositories\CheckInRepository;
use App\AccessControl\Domain\Entities\CheckIn;
use Illuminate\Support\Facades\DB;

class EloquentCheckInRepository implements CheckInRepository
{
    public function save(CheckIn $checkIn): void
    {
        DB::table('check_ins')->insert([
            'id' => $checkIn->id,
            'user_id' => $checkIn->userId,
            'checked_in_at' => $checkIn->checkedInAt->format('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

namespace App\Engagement\Application\Listeners;

use App\AccessControl\Domain\Events\UserCheckedIn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateDashboardProjection
{
    public function handle(UserCheckedIn $event): void
    {
        DB::table('dashboard_view')->updateOrInsert(
            ['check_in_id' => $event->checkInId],
            [
                'user_id' => $event->userId,
                'checked_in_at' => Carbon::parse($event->occurredAt)->toDateTimeString(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}

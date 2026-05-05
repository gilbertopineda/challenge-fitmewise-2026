<?php

namespace App\Engagement\Application\Listeners;

use App\Engagement\Domain\Events\QuoteAssigned;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateDashboardProjection
{
    public function handle(QuoteAssigned $event): void
    {
        DB::table('dashboard_view')->updateOrInsert(
            ['check_in_id' => $event->checkInId],
            [
                'user_id' => $event->userId,
                'checked_in_at' => Carbon::parse($event->occurredAt)->toDateTimeString(),
                'quote' => $event->quote,
                'author' => $event->author,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}

<?php

namespace App\Engagement\Application\Queries;

use Illuminate\Support\Facades\DB;

class GetUserDashboardHandler
{
    public function handle(GetUserDashboardQuery $query)
    {
        return DB::table('dashboard_view')
            ->where('user_id', $query->userId)
            ->orderByDesc('checked_in_at')
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Engagement\Application\Queries\GetUserDashboardQuery;
use App\Engagement\Application\Queries\GetUserDashboardHandler;

class DashboardController extends Controller
{
    public function __construct(
        private GetUserDashboardHandler $handler
    )
    {
    }

    public function __invoke(string $userId)
    {
        $query = new GetUserDashboardQuery($userId);

        return response()->json(
            $this->handler->handle($query)
        );
    }
}

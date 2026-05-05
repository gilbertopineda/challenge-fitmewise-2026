<?php

namespace App\Engagement\Application\Queries;

class GetUserDashboardQuery
{
    public function __construct(
        public string $userId
    )
    {
    }
}

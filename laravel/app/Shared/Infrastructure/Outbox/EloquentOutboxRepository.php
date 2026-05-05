<?php

namespace App\Shared\Infrastructure\Outbox;

use Illuminate\Support\Facades\DB;

class EloquentOutboxRepository implements OutboxRepository
{
    public function store(object $event): void
    {
        DB::table('outbox_events')->insert([
            'event_type' => get_class($event),
            'payload' => json_encode($event),
            'created_at' => now(),
        ]);
    }
}

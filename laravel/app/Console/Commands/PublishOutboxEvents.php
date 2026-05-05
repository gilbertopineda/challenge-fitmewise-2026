<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Shared\Infrastructure\Bus\GenericEventJob;

class PublishOutboxEvents extends Command
{
    protected $signature = 'outbox:publish';
    protected $description = 'Publish pending outbox events to queue';

    public function handle()
    {
        $events = DB::table('outbox_events')
            ->whereNull('processed_at')
            ->limit(50)
            ->get();

        foreach ($events as $event) {

            dispatch(new GenericEventJob(
                $event->event_type,
                json_decode($event->payload, true)
            ));

            DB::table('outbox_events')
                ->where('id', $event->id)
                ->update([
                    'processed_at' => now()
                ]);
        }

        $this->info("Published {$events->count()} events.");
    }
}

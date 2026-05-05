<?php

namespace App\Shared\Infrastructure\Bus;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenericEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 5;
    public int $backoff = 10;

    public function __construct(
        public string $eventType,
        public array  $payload
    )
    {
    }

    public function handle()
    {
        $eventClass = $this->eventType;

        $event = new $eventClass(
            $this->payload['checkInId'],
            $this->payload['userId'],
            $this->payload['occurredAt']
        );

        event($event);
    }
}

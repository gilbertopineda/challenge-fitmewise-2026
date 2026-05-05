<?php

namespace App\Shared\Infrastructure\Outbox;

interface OutboxRepository
{
    public function store(object $event): void;
}

<?php

namespace App\AccessControl\Application\Handlers;

use App\AccessControl\Application\Commands\CheckInUserCommand;
use App\AccessControl\Domain\Events\UserCheckedIn;
use App\AccessControl\Domain\Repositories\CheckInRepository;
use App\AccessControl\Domain\Entities\CheckIn;
use App\Shared\Infrastructure\Outbox\OutboxRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckInUserHandler
{
    public function __construct(
        private CheckInRepository $repository,
        private OutboxRepository  $outbox
    )
    {
    }

    public function handle(CheckInUserCommand $command): void
    {
        DB::transaction(function () use ($command) {

            $checkIn = new CheckIn(
                id: (string)Str::uuid(),
                userId: $command->userId,
                checkedInAt: new \DateTimeImmutable()
            );

            $this->repository->save($checkIn);

            $event = new UserCheckedIn(
                $checkIn->id,
                $checkIn->userId,
                now()->toISOString()
            );

            $this->outbox->store($event);
        });
    }
}

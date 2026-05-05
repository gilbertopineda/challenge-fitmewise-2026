<?php

namespace App\Engagement\Application\Listeners;

use App\AccessControl\Domain\Events\UserCheckedIn;
use App\Engagement\Domain\Ports\QuoteServicePort;
use App\Engagement\Domain\Events\QuoteAssigned;
use Illuminate\Support\Facades\DB;

class AssignMotivationalQuote
{
    public function __construct(
        private QuoteServicePort $quoteService
    )
    {
    }

    public function handle(UserCheckedIn $event): void
    {
        $quote = $this->quoteService->getRandomQuote();

        DB::table('engagement_quotes')->updateOrInsert(
            ['check_in_id' => $event->checkInId],
            [
                'user_id' => $event->userId,
                'quote' => $quote->text,
                'author' => $quote->author,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        event(new QuoteAssigned(
            $event->checkInId,
            $event->userId,
            $quote->text,
            $quote->author,
            $event->occurredAt
        ));
    }
}

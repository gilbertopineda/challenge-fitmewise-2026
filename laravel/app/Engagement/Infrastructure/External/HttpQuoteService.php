<?php

namespace App\Engagement\Infrastructure\External;

use App\Engagement\Domain\Ports\QuoteServicePort;
use App\Engagement\Domain\ValueObjects\Quote;
use Illuminate\Support\Facades\Http;

class HttpQuoteService implements QuoteServicePort
{
    public function getRandomQuote(): Quote
    {
        try {
            $response = Http::timeout(2)
                ->get('https://dummyjson.com/quotes/random');

            if ($response->failed()) {
                return new Quote('Stay strong', 'System');
            }

            return new Quote(
                $response->json()['quote'] ?? 'Keep going',
                $response->json()['author'] ?? 'Unknown'
            );

        } catch (\Throwable $e) {
            return new Quote('Every step counts', 'System');
        }
    }
}

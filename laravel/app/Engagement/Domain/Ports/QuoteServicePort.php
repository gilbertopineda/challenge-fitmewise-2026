<?php

namespace App\Engagement\Domain\Ports;

use App\Engagement\Domain\ValueObjects\Quote;

interface QuoteServicePort
{
    public function getRandomQuote(): Quote;
}

<?php

namespace App\Providers;

use App\AccessControl\Domain\Events\UserCheckedIn;
use App\AccessControl\Domain\Repositories\CheckInRepository;
use App\AccessControl\Infrastructure\Persistence\EloquentCheckInRepository;
use App\Engagement\Application\Listeners\AssignMotivationalQuote;
use App\Engagement\Application\Listeners\CreateDashboardProjection;
use App\Engagement\Application\Listeners\UpdateDashboardProjection;
use App\Engagement\Domain\Ports\QuoteServicePort;
use App\Engagement\Domain\Events\QuoteAssigned;
use App\Engagement\Infrastructure\External\HttpQuoteService;
use App\Shared\Infrastructure\Outbox\EloquentOutboxRepository;
use App\Shared\Infrastructure\Outbox\OutboxRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CheckInRepository::class,
            EloquentCheckInRepository::class
        );

        $this->app->bind(
            OutboxRepository::class,
            EloquentOutboxRepository::class
        );

        $this->app->bind(
            QuoteServicePort::class,
            HttpQuoteService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            UserCheckedIn::class,
            AssignMotivationalQuote::class
        );

        Event::listen(
            UserCheckedIn::class,
            CreateDashboardProjection::class
        );

        Event::listen(
            QuoteAssigned::class,
            UpdateDashboardProjection::class
        );
    }
}

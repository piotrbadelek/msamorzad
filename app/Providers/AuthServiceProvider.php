<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Message;
use App\Models\Payment;
use App\Policies\MessagePolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Payment::class => PaymentPolicy::class,
		Message::class => MessagePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Calculation;
use App\Models\User;
use App\Models\Feedback;
use App\Models\Faq;
use App\Policies\CalculationPolicy;
use App\Policies\UserPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\FaqPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Calculation::class => CalculationPolicy::class,
        User::class => UserPolicy::class,
        Feedback::class => FeedbackPolicy::class,
        Faq::class => FaqPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define admin gate
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        // Define calculation ownership gate (fallback)
        Gate::define('manage-calculation', function (User $user, Calculation $calculation) {
            return $user->id === $calculation->user_id || $user->isAdmin();
        });

        // Define global admin gate
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });
    }
}
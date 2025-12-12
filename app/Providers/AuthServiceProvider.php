<?php

namespace App\Providers;

use App\Models\JobListing;
use App\Policies\JobListingPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        JobListing::class => JobListingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();
        // ResetPassword
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = route('password.reset', $token) . '?email=' . $notifiable->getEmailForPasswordReset();
            return (new MailMessage())
                ->subject('Password Reset')
                ->view('emails.reset', compact('url'));
        });
    }
}

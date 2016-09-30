<?php

namespace App\Providers;


use App\Domain\Model\Email;
use App\Jobs\CutDomain;
use App\Jobs\EmailValidationJobs;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use DispatchesJobs;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Email::created(function (Email $email) {
            dispatch(new CutDomain($email));
            dispatch(new EmailValidationJobs($email));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}

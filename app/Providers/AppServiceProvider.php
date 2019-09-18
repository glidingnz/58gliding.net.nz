<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Member;
use App\Observers\MemberModelObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('mailgun.client', function() {
            return \Http\Adapter\Guzzle6\Client::createWithConfig([
            
            ]);
        });

        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Member::observe(MemberModelObserver::class);
    }
}

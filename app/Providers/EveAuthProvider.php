<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Evelabs\OAuth2\Client\Provider\EveOnline;

class EveOnlinePersonal extends EveOnline
{
    
}

class EveOnlineCorporation extends EveOnline
{
    
}


class EveAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('App\Providers\EveOnlinePersonal', function ($app) {
          return new EveOnlinePersonal([
            'clientId'          => '6c37f7a5d84e4185afe47a623d947d70',
            'clientSecret'      => 'yejgJmApQXLhe40IPn1d1x0A8xrZqDvFZ34nBzNq',
            'redirectUri'       => 'http://spy2.rspace.akturide.beget.tech/auth/callback',
            ]);
        });
        $this->app->singleton('App\Providers\EveOnlineCorporation', function ($app) {
          return new EveOnlineCorporation([
            'clientId'          => 'de84a7a509d24fa2b0562be5274cb07a',
            'clientSecret'      => 'ZBpe7vVAs25d0DxClitGYPJ4FBhpL19gi0d15zDp',
            'redirectUri'       => 'http://spy2.rspace.akturide.beget.tech/auth/callback2',
            ]);
        });
    }  
}

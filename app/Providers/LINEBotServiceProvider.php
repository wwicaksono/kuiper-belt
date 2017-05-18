<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LINEBotServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(LINEBot::class, function () {
            $channelAccess = env('CHANNEL_ACCESS');
            $channelSecret = env('CHANNEL_SECRET');

            return new LINEBot(new CurlHTTPClient($channelAccess), [
                'channelSecret' => $channelSecret
            ]);
        });
    }
}

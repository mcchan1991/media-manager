<?php

namespace Encore\Admin\Media;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-admin-media');
        $this->publishes([
            __DIR__.'/../resources/js' => public_path('/vendor/laravel-admin-ext-media'),
        ], 'laravel-admin-ext--media-assets');
        MediaManager::boot();
    }
}

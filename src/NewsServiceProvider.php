<?php

namespace Vgplay\News;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Vgplay\News\Models\Category;
use Vgplay\News\Models\Post;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [
            Post::class => config('vgplay.news.posts.policy'),
            Category::class => config('vgplay.news.categories.policy'),
        ];
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'vgplay');
    }

    public function boot()
    {
        $this->registerPolicies();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'vgplay');

        $this->publishes([
            __DIR__ . '/../resources/assets/vendor' => public_path('vendor')
        ], 'assets');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/vgplay')
        ], 'views');
    }
}

<?php

namespace Sloveniangooner\LocaleAnywhere;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Sloveniangooner\LocaleAnywhere\Http\Middleware\Authorize;

class LocaleAnywhereServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function () {
            Nova::script('locale-anywhere', __DIR__ . '/../dist/js/locale-anywhere.js');

            /** @var \Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver $localeResolver */
            $localeResolver = App::make(Contracts\LocaleResolver::class);

            Nova::provideToScript([
                'localeAnywhere' => [
                    'customDetailToolbar' => Config::get('locale-anywhere.custom_detail_toolbar'),
                    'currentLocale' => $localeResolver->getLocale(),
                ],
            ]);
        });

        $this->app->booted(function () {
            $this->routes();
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/locale-anywhere.php' => config_path('locale-anywhere.php'),
            ]);
        }
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/locale-anywhere')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/locale-anywhere.php', 'locale-anywhere');

        $this->app->singleton(
            Contracts\LocaleResolver::class,
            Config::get('locale-anywhere.locale_resolver', CacheLocaleResolver::class)
        );

        $this->app->singleton(
            Contracts\LocalesProvider::class,
            Config::get('locale-anywhere.locales_provider', ConfigLocalesProvider::class)
        );
    }
}

<?php

namespace App\Providers;

use App\Http\RDFRequest;
use App\Services\OntologiesManager;
use App\Support\Macros\BetterMacros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ontologies', OntologiesManager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        BetterMacros::mixin(Request::class, RDFRequest::class);
        Blade::directive('markdown', function ($expression) {
            return "<?php echo markdown_blade($expression); ?>";
        });
    }
}

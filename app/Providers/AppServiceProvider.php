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
     */
    public function register(): void
    {
        $this->app->singleton('ontologies', OntologiesManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BetterMacros::mixin(Request::class, RDFRequest::class);
        Blade::directive('markdown', function (?string $expression): string {
            return "<?php echo markdown_blade({$expression}); ?>";
        });
    }
}

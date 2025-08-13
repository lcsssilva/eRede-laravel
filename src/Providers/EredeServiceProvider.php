<?php

namespace Lcs13761\EredeLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Lcs13761\EredeLaravel\Contracts\EredeServiceInterface;
use Lcs13761\EredeLaravel\Contracts\HttpClientInterface;
use Lcs13761\EredeLaravel\DTOs\StoreConfigDTO;
use Lcs13761\EredeLaravel\Http\EredeHttpClient;
use Lcs13761\EredeLaravel\Services\EredeService;

class EredeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Mesclar configurações
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/erede.php',
            'erede'
        );

        // Registrar o serviço
        $this->app->singleton(EredeService::class, function ($app) {
            return StoreConfigDTO::fromConfig($app['config']['erede']);
        });

        $this->app->singleton(HttpClientInterface::class, EredeHttpClient::class);

        $this->app->singleton(EredeServiceInterface::class, EredeService::class);

        $this->app->alias(EredeServiceInterface::class, 'erede');

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publicar arquivos de configuração
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/erede.php' => config_path('erede.php'),
            ], 'erede-config');
        }

        // Carregar rotas
//        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

    }
}
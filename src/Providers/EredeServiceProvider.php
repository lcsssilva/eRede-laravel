<?php

namespace Lcsssilva\EredeLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Lcsssilva\EredeLaravel\Contracts\EredeServiceInterface;
use Lcsssilva\EredeLaravel\Contracts\HttpClientInterface;
use Lcsssilva\EredeLaravel\DTOs\StoreConfigDTO;
use Lcsssilva\EredeLaravel\Http\EredeHttpClient;
use Lcsssilva\EredeLaravel\Services\EredeService;

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
        $this->app->singleton(StoreConfigDTO::class, function ($app) {
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
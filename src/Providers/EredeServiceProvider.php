<?php

namespace Lcs13761\EredeLaravel\Providers;


use Illuminate\Support\ServiceProvider;
use Lcs13761\EredeLaravel\EredeService;

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
            return new EredeService(
                config('erede.filiation'),
                config('erede.api_token')
            );
        });

        // Alias para facilitar o uso
        $this->app->alias(EredeService::class, 'erede');
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
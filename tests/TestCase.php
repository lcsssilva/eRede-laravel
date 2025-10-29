<?php

namespace Lcsssilva\EredeLaravel\Tests;


use Orchestra\Testbench\TestCase as Orchestra;

use Lcsssilva\EredeLaravel\Providers\EredeServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            EredeServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Erede' => \Lcsssilva\EredeLaravel\Facades\Erede::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('erede.filiation', 'test-filiation');
        config()->set('erede.token', 'test-token');
        config()->set('erede.sandbox', true);
    }
}

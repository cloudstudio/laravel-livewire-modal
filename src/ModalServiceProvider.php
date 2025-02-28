<?php

namespace Cloudstudio\Modal;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Cloudstudio\Modal\Services\ModalEventService;
use Cloudstudio\Modal\Services\ModalConfigService;
use Cloudstudio\Modal\Services\ModalManagerService;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Service provider for the Laravel Livewire Modal package.
 */
class ModalServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     *
     * @param  \Spatie\LaravelPackageTools\Package  $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-livewire-modal')
            ->hasConfigFile()
            ->hasViews('laravel-livewire-modal')
            ->hasAssets();
    }

    /**
     * Perform post-registration booting.
     *
     * @return void
     */
    public function bootingPackage(): void
    {
        $this->registerComponents();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function registeringPackage(): void
    {
        $this->registerServices();
    }

    /**
     * Register Livewire components.
     *
     * @return void
     */
    protected function registerComponents(): void
    {
        Livewire::component('modal', ModalContainer::class);
    }

    /**
     * Register package services.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton(ModalConfigService::class, function ($app) {
            return new ModalConfigService();
        });

        $this->app->singleton(ModalEventService::class, function ($app) {
            return new ModalEventService();
        });

        $this->app->singleton(ModalManagerService::class, function ($app) {
            return new ModalManagerService();
        });
    }
}

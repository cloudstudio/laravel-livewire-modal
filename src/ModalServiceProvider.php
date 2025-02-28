<?php

namespace Cloudstudio\Modal;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * @see \Cloudstudio\Modal\ModalServiceProvider
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
     * Register Livewire components.
     *
     * @return void
     */
    protected function registerComponents(): void
    {
        Livewire::component('modal', ModalContainer::class);
    }
}

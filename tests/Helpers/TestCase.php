<?php

namespace Cloudstudio\Modal\Tests\Helpers;

use Cloudstudio\Modal\ModalServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Cloudstudio\\Modal\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            ModalServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // Set up app key for encryption
        $app['config']->set('app.key', 'base64:'.base64_encode(
            'Cloudstudio\Modal\Tests\Helpers\TestCase'
        ));

        // Set up database for testing
        $app['config']->set('database.default', 'testing');

        // Set up the modal config
        $app['config']->set('livewire-modal.component_defaults', [
            'modal_max_width' => '2xl',
            'close_modal_on_click_away' => true,
            'close_modal_on_escape' => true,
            'close_modal_on_escape_is_forceful' => true,
            'dispatch_close_event' => false,
            'destroy_on_close' => false,
        ]);
    }
}

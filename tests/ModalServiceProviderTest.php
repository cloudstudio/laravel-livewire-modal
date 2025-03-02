<?php

use Cloudstudio\Modal\Facades\Modal;
use Cloudstudio\Modal\ModalContainer;
use Cloudstudio\Modal\Services\ModalConfigService;
use Cloudstudio\Modal\Services\ModalEventService;
use Cloudstudio\Modal\Services\ModalManagerService;

// Test package service registration
it('registers the package services', function () {
    // Test facade
    expect(Modal::getFacadeRoot())->toBeInstanceOf(ModalContainer::class);

    // Test bound services
    expect(app(ModalConfigService::class))->toBeInstanceOf(ModalConfigService::class);
    expect(app(ModalEventService::class))->toBeInstanceOf(ModalEventService::class);
    expect(app(ModalManagerService::class))->toBeInstanceOf(ModalManagerService::class);
});

// Test view component registration
it('registers the container component', function () {
    // This should be registered as the alias for the ModalContainer
    $livewireManager = app()->make(\Livewire\Mechanisms\ComponentRegistry::class);

    expect($livewireManager->getClass('modal'))
        ->toBe(ModalContainer::class);
});

// Test config is properly loaded
it('loads the proper configuration', function () {
    $config = config('livewire-modal.component_defaults');

    expect($config)->toBeArray()
        ->and($config)->toHaveKeys([
            'modal_max_width',
            'close_modal_on_click_away',
            'close_modal_on_escape',
            'close_modal_on_escape_is_forceful',
            'dispatch_close_event',
            'destroy_on_close',
        ]);
});

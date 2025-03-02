<?php

namespace Cloudstudio\Modal\Tests\Services;

use Cloudstudio\Modal\Services\ModalConfigService;

// Test the config service with default values
it('returns default config values', function () {
    // Setup test config
    config()->set('livewire-modal.component_defaults', [
        'modal_max_width' => 'test-width',
        'close_modal_on_click_away' => true,
        'close_modal_on_escape' => true,
        'close_modal_on_escape_is_forceful' => true,
        'dispatch_close_event' => false,
        'destroy_on_close' => false,
    ]);

    $service = new ModalConfigService;

    expect($service->getModalMaxWidth())->toBe('test-width')
        ->and($service->shouldCloseModalOnClickAway())->toBeTrue()
        ->and($service->shouldCloseModalOnEscape())->toBeTrue()
        ->and($service->isCloseModalOnEscapeForceful())->toBeTrue()
        ->and($service->shouldDispatchCloseEvent())->toBeFalse()
        ->and($service->shouldDestroyOnClose())->toBeFalse();
});

// Test the config service with custom values
it('returns custom config values', function () {
    // Setup test config
    config()->set('livewire-modal.component_defaults', [
        'modal_max_width' => 'custom-width',
        'close_modal_on_click_away' => false,
        'close_modal_on_escape' => false,
        'close_modal_on_escape_is_forceful' => false,
        'dispatch_close_event' => true,
        'destroy_on_close' => true,
    ]);

    $service = new ModalConfigService;

    expect($service->getModalMaxWidth())->toBe('custom-width')
        ->and($service->shouldCloseModalOnClickAway())->toBeFalse()
        ->and($service->shouldCloseModalOnEscape())->toBeFalse()
        ->and($service->isCloseModalOnEscapeForceful())->toBeFalse()
        ->and($service->shouldDispatchCloseEvent())->toBeTrue()
        ->and($service->shouldDestroyOnClose())->toBeTrue();
});

// Test the getModalMaxWidthClass method
it('returns correct CSS class for modal width', function () {
    $service = new ModalConfigService;

    // Test some sample widths
    expect($service->getModalMaxWidthClass('sm'))->toBe('sm:max-w-sm')
        ->and($service->getModalMaxWidthClass('md'))->toBe('sm:max-w-md')
        ->and($service->getModalMaxWidthClass('lg'))->toBe('sm:max-w-md md:max-w-lg')
        ->and($service->getModalMaxWidthClass('xl'))->toBe('sm:max-w-md md:max-w-xl')
        ->and($service->getModalMaxWidthClass('2xl'))->toBe('sm:max-w-md md:max-w-xl lg:max-w-2xl');
});

// Test invalid width throws exception
it('throws exception for invalid width', function () {
    $service = new ModalConfigService;
    $service->getModalMaxWidthClass('invalid-width');
})->throws(\InvalidArgumentException::class);

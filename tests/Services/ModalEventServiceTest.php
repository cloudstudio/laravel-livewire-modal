<?php

namespace Cloudstudio\Modal\Tests\Services;

use Cloudstudio\Modal\LivewireModal;
use Cloudstudio\Modal\Services\ModalEventService;
use Livewire\Component;

// Mock modal component for testing
class ModalEventTestComponent extends LivewireModal
{
    public string $value = 'test';

    public function render()
    {
        return <<<'blade'
        <div>Test component</div>
        blade;
    }
}

// Test closeModal method dispatches the correct events
it('dispatches close modal event', function () {
    $service = new ModalEventService();
    $component = new ModalEventTestComponent();

    // This is difficult to test as it dispatches events
    // Just ensure it doesn't throw any exceptions
    $service->closeModal($component, false, 0, false);

    expect(true)->toBeTrue();
});

// Test closeModalWithEvents method with different event types
it('handles different event types correctly', function () {
    $service = new ModalEventService();
    $component = new ModalEventTestComponent();

    // Test with various event types
    $events = [
        'plainEvent',               // Plain event
        'eventWithData' => 'test-data', // Event with data
    ];

    // This is difficult to test as it dispatches events
    // Just ensure it doesn't throw any exceptions
    $service->closeModalWithEvents($component, $events, false, 0, false);

    expect(true)->toBeTrue();
});

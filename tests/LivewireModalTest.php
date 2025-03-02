<?php

use Cloudstudio\Modal\LivewireModal;
use Cloudstudio\Modal\Services\ModalConfigService;
use Cloudstudio\Modal\Services\ModalEventService;
use Mockery;

// Test modal component for event testing
class EventModalComponent extends LivewireModal
{
    public string $testProp = 'test';
    public bool $forceClose = false;
    public int $skipModals = 0;
    public bool $destroySkipped = false;

    public function close()
    {
        $this->closeModal();
    }

    public function closeWithEvents(array $events)
    {
        $this->closeModalWithEvents($events);
    }

    public function render()
    {
        return <<<'blade'
        <div>
            <button wire:click="close">Close</button>
            <button wire:click="closeWithEvents([])">Close With Events</button>
        </div>
        blade;
    }
}

// Test modal component with custom configuration
class CustomConfigModalComponent extends LivewireModal
{
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function closeModalOnEscape(): bool
    {
        return false;
    }

    public static function closeModalOnEscapeIsForceful(): bool
    {
        return false;
    }

    public static function dispatchCloseEvent(): bool
    {
        return true;
    }

    public static function destroyOnClose(): bool
    {
        return true;
    }

    public function render()
    {
        return <<<'blade'
        <div>Custom config modal</div>
        blade;
    }
}

// Test the default configuration methods
it('has default configuration values', function () {
    $defaultConfig = app(ModalConfigService::class);

    // Test default values match the config
    expect(EventModalComponent::modalMaxWidth())->toBe($defaultConfig->getModalMaxWidth())
        ->and(EventModalComponent::closeModalOnClickAway())->toBe($defaultConfig->shouldCloseModalOnClickAway())
        ->and(EventModalComponent::closeModalOnEscape())->toBe($defaultConfig->shouldCloseModalOnEscape())
        ->and(EventModalComponent::closeModalOnEscapeIsForceful())->toBe($defaultConfig->isCloseModalOnEscapeForceful())
        ->and(EventModalComponent::dispatchCloseEvent())->toBe($defaultConfig->shouldDispatchCloseEvent())
        ->and(EventModalComponent::destroyOnClose())->toBe($defaultConfig->shouldDestroyOnClose());
});

// Test modal max width class
it('generates the correct CSS class for modal width', function () {
    $configService = app(ModalConfigService::class);

    expect(EventModalComponent::modalMaxWidthClass())
        ->toBe($configService->getModalMaxWidthClass(EventModalComponent::modalMaxWidth()));
});

// Test custom configuration
it('can override default configuration', function () {
    expect(CustomConfigModalComponent::modalMaxWidth())->toBe('xl')
        ->and(CustomConfigModalComponent::closeModalOnClickAway())->toBeFalse()
        ->and(CustomConfigModalComponent::closeModalOnEscape())->toBeFalse()
        ->and(CustomConfigModalComponent::closeModalOnEscapeIsForceful())->toBeFalse()
        ->and(CustomConfigModalComponent::dispatchCloseEvent())->toBeTrue()
        ->and(CustomConfigModalComponent::destroyOnClose())->toBeTrue();
});

// Test skipPreviousModal method
it('can skip previous modals', function () {
    $component = new EventModalComponent();

    // Skip one modal
    $component->skipPreviousModal();
    expect($component->skipModals)->toBe(1)
        ->and($component->destroySkipped)->toBeFalse();

    // Skip multiple modals with destroy
    $component->skipPreviousModal(2, true);
    expect($component->skipModals)->toBe(2)
        ->and($component->destroySkipped)->toBeTrue();
});

// Test skipPreviousModals method
it('can skip multiple previous modals', function () {
    $component = new EventModalComponent();

    // Skip multiple modals
    $component->skipPreviousModals(3);
    expect($component->skipModals)->toBe(3);

    // Skip with destroy
    $component->skipPreviousModals(2, true);
    expect($component->skipModals)->toBe(2)
        ->and($component->destroySkipped)->toBeTrue();
});

// Test destroySkippedModals method
it('can mark skipped modals for destruction', function () {
    $component = new EventModalComponent();

    $component->destroySkippedModals();
    expect($component->destroySkipped)->toBeTrue();
});

// Test forceClose method
it('can force close the modal', function () {
    $component = new EventModalComponent();

    $component->forceClose();
    expect($component->forceClose)->toBeTrue();
});

// Test closeModal method calls the event service
it('calls the event service when closing a modal', function () {
    $mockEventService = Mockery::mock(ModalEventService::class);
    $mockEventService->shouldReceive('closeModal')
        ->once()
        ->with(Mockery::type(EventModalComponent::class), false, 0, false);

    app()->instance(ModalEventService::class, $mockEventService);

    $component = new EventModalComponent();
    $component->closeModal();

    // Mockery will verify the expectations when we close it
    expect(true)->toBeTrue(); // Simple assertion to avoid risky test

    Mockery::close();
});

// Test closeModalWithEvents method calls the event service
it('calls the event service when closing a modal with events', function () {
    $mockEventService = Mockery::mock(ModalEventService::class);
    $events = ['testEvent' => 'testData'];

    $mockEventService->shouldReceive('closeModalWithEvents')
        ->once()
        ->with(Mockery::type(EventModalComponent::class), $events, false, 0, false);

    app()->instance(ModalEventService::class, $mockEventService);

    $component = new EventModalComponent();
    $component->closeModalWithEvents($events);

    // Mockery will verify the expectations when we close it
    expect(true)->toBeTrue(); // Simple assertion to avoid risky test

    Mockery::close();
});

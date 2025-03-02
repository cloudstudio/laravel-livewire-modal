<?php

namespace Cloudstudio\Modal\Tests\Services;

use Cloudstudio\Modal\LivewireModal;
use Cloudstudio\Modal\Services\ModalManagerService;

// Mock modal component for testing
class ManagerTestComponent extends LivewireModal
{
    public string $name = 'test';

    public function mount(?string $customName = null)
    {
        if ($customName) {
            $this->name = $customName;
        }
    }

    public function render()
    {
        return <<<'blade'
        <div>{{ $name }}</div>
        blade;
    }
}

beforeEach(function () {
    app()->make(\Livewire\Mechanisms\ComponentRegistry::class)
        ->component('manager-test-component', ManagerTestComponent::class);
});

// Test createModalComponent creates the expected result
it('creates modal component data', function () {
    $service = new ModalManagerService;

    $component = 'manager-test-component';
    $args = ['customName' => 'Custom Test Name'];
    $attributes = ['customAttribute' => 'value'];

    $result = $service->createModalComponent($component, $args, $attributes);

    expect($result)->toBeArray()
        ->and($result)->toHaveKeys(['id', 'data'])
        ->and($result['data'])->toBeArray()
        ->and($result['data'])->toHaveKeys(['name', 'arguments', 'modalAttributes']);

    expect($result['data']['name'])->toBe($component)
        ->and($result['data']['arguments'])->toBe($args);

    // Check that modalAttributes contains both the custom attributes and some defaults
    expect($result['data']['modalAttributes'])->toHaveKey('customAttribute', 'value')
        ->and($result['data']['modalAttributes'])->toHaveKey('closeOnClickAway')
        ->and($result['data']['modalAttributes'])->toHaveKey('closeOnEscape')
        ->and($result['data']['modalAttributes'])->toHaveKey('closeOnEscapeIsForceful')
        ->and($result['data']['modalAttributes'])->toHaveKey('dispatchCloseEvent')
        ->and($result['data']['modalAttributes'])->toHaveKey('destroyOnClose')
        ->and($result['data']['modalAttributes'])->toHaveKey('maxWidth')
        ->and($result['data']['modalAttributes'])->toHaveKey('maxWidthClass');

    // Id should be a non-empty string
    expect($result['id'])->toBeString()->not->toBeEmpty();
});

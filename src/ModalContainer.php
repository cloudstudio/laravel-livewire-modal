<?php

namespace Cloudstudio\Modal;

use Cloudstudio\Modal\Services\ModalManagerService;
use Livewire\Component;

/**
 * Modal container component.
 */
class ModalContainer extends Component
{
    /**
     * The active component ID.
     *
     * @var string|null
     */
    public ?string $activeComponent = null;

    /**
     * The components array.
     *
     * @var array
     */
    public array $components = [];

    /**
     * The modal manager service.
     *
     * @var \Cloudstudio\Modal\Services\ModalManagerService|null
     */
    protected ?ModalManagerService $managerService = null;

    /**
     * Get the modal manager service.
     *
     * @return \Cloudstudio\Modal\Services\ModalManagerService
     */
    protected function managerService(): ModalManagerService
    {
        if ($this->managerService === null) {
            $this->managerService = app(ModalManagerService::class);
        }

        return $this->managerService;
    }

    /**
     * Reset the state.
     *
     * @return void
     */
    public function resetState(): void
    {
        $this->components = [];
        $this->activeComponent = null;
    }

    /**
     * Open a modal.
     *
     * @param  string  $component  The component name
     * @param  array  $arguments  The component arguments
     * @param  array  $modalAttributes  Additional modal attributes
     * @return void
     */
    public function openModal(string $component, array $arguments = [], array $modalAttributes = []): void
    {
        $result = $this->managerService()->createModalComponent($component, $arguments, $modalAttributes);
        $id = $result['id'];
        $this->components[$id] = $result['data'];
        $this->activeComponent = $id;

        $this->dispatch('activeModalComponentChanged', id: $id);
    }

    /**
     * Destroy a component.
     *
     * @param  string  $id  The component ID
     * @return void
     */
    public function destroyComponent(string $id): void
    {
        unset($this->components[$id]);
    }

    /**
     * Get the listeners.
     *
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        return [
            'openModal',
            'destroyComponent',
        ];
    }

    /**
     * Should show modal.
     * Checks if a modal component should be displayed based on its attributes and the container's state.
     *
     * @param  array<string, mixed>  $componentAttributes  The component attributes
     * @param  bool  $modalFlyout  Whether the container rendering this component is a flyout.
     * @param  string|null  $modalFlyoutPosition  The position of the flyout container ('right', 'left', 'bottom').
     * @return bool Whether the modal should be shown.
     */
    public function shouldShowModal(array $componentAttributes, bool $modalFlyout, ?string $modalFlyoutPosition = null): bool
    {
        $isComponentFlyout = $componentAttributes['modalFlyout'] ?? false;
        $componentFlyoutPosition = $componentAttributes['modalFlyoutPosition'] ?? null;

        // Case 1: Standard Modal Container
        // If the container is NOT a flyout, show the component only if it's also NOT a flyout.
        if (! $modalFlyout) {
            return ! $isComponentFlyout;
        }

        // Case 2: Flyout Container
        // If the container IS a flyout, show the component only if:
        // 1. The component itself IS ALSO a flyout.
        // 2. Their positions match.
        if (! $isComponentFlyout) {
            return false; // Component is not a flyout, so don't show it in a flyout container.
        }

        // Both container and component are flyouts, check positions.
        return match ($modalFlyoutPosition) {
            'right', 'left', 'bottom' => $componentFlyoutPosition === $modalFlyoutPosition,
            default => false, // Invalid or no position specified for the container.
        };
    }

    /**
     * Render the modal container.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('laravel-livewire-modal::modal', [
            'modalScript' => __DIR__ . '/../dist/modal.js',
        ]);
    }
}

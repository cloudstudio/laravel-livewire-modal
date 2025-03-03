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
     */
    public ?string $activeComponent = null;

    /**
     * The components array.
     */
    public array $components = [];

    /**
     * The modal manager service.
     */
    protected ?ModalManagerService $managerService = null;

    /**
     * Get the modal manager service.
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
     */
    public function destroyComponent(string $id): void
    {
        unset($this->components[$id]);
    }

    /**
     * Get the listeners.
     */
    public function getListeners(): array
    {
        return [
            'openModal',
            'destroyComponent',
        ];
    }

    /**
     * Render the modal container.
     */
    public function render(): \Illuminate\View\View
    {
        return view('laravel-livewire-modal::modal', [
            'modalScript' => __DIR__ . '/../dist/modal.js',
        ]);
    }
}

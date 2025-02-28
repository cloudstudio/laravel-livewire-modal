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
     * @var ModalManagerService|null
     */
    protected ?ModalManagerService $managerService = null;

    /**
     * Get the modal manager service.
     *
     * @return ModalManagerService
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
     * @param string $component The component name
     * @param array $arguments The component arguments
     * @param array $modalAttributes Additional modal attributes
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
     * @param string $id The component ID
     * @return void
     */
    public function destroyComponent(string $id): void
    {
        unset($this->components[$id]);
    }

    /**
     * Get the listeners.
     *
     * @return array
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

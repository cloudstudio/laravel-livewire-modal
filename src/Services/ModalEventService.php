<?php

namespace Cloudstudio\Modal\Services;

use Livewire\Component;

/**
 * Service for handling modal events.
 */
class ModalEventService
{
    /**
     * Emit modal events.
     *
     * @param  Component  $component  The component instance
     * @param  array  $events  The events to emit
     */
    public function emitModalEvents(Component $component, array $events): void
    {
        foreach ($events as $targetComponent => $event) {
            if (is_array($event)) {
                [$event, $params] = $event;
            }

            if (is_numeric($targetComponent)) {
                $component->dispatch($event, ...$params ?? []);
            } else {
                $component->dispatch($event, ...$params ?? [])->to($targetComponent);
            }
        }
    }

    /**
     * Close modal with events.
     *
     * @param  Component  $component  The component instance
     * @param  array  $events  The events to emit
     * @param  bool  $force  Whether to force close
     * @param  int  $skipModals  Number of modals to skip
     * @param  bool  $destroySkipped  Whether to destroy skipped modals
     */
    public function closeModalWithEvents(Component $component, array $events, bool $force = false, int $skipModals = 0, bool $destroySkipped = false): void
    {
        $this->emitModalEvents($component, $events);
        $this->closeModal($component, $force, $skipModals, $destroySkipped);
    }

    /**
     * Close modal.
     *
     * @param  Component  $component  The component instance
     * @param  bool  $force  Whether to force close
     * @param  int  $skipModals  Number of modals to skip
     * @param  bool  $destroySkipped  Whether to destroy skipped modals
     */
    public function closeModal(Component $component, bool $force = false, int $skipModals = 0, bool $destroySkipped = false): void
    {
        $component->dispatch('closeModal', force: $force, skipPreviousModals: $skipModals, destroySkipped: $destroySkipped);
    }
}

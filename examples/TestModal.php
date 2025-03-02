<?php

namespace App\Livewire;

use Cloudstudio\Modal\LivewireModal;
use Illuminate\View\View;

class TestModal extends LivewireModal
{
    /**
     * Example public property
     */
    public string $message = 'Hello from the modal!';

    /**
     * Initialize the component
     */
    public function mount(?string $customMessage = null): void
    {
        if ($customMessage) {
            $this->message = $customMessage;
        }
    }

    /**
     * Close the modal with a custom event
     */
    public function confirm(): void
    {
        $this->closeModalWithEvents([
            'modalConfirmed' => $this->message,
        ]);
    }

    /**
     * Simply close the modal
     */
    public function cancel(): void
    {
        $this->closeModal();
    }

    /**
     * Override the default modal width
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        return view('livewire.test-modal');
    }
}

<?php

namespace App\Livewire;

use Cloudstudio\Modal\LivewireModal;
use Illuminate\View\View;

class TestModal extends LivewireModal
{
    /**
     * Example public property
     *
     * @var string
     */
    public string $message = 'Hello from the modal!';

    /**
     * Initialize the component
     *
     * @param string|null $customMessage
     * @return void
     */
    public function mount(?string $customMessage = null): void
    {
        if ($customMessage) {
            $this->message = $customMessage;
        }
    }

    /**
     * Close the modal with a custom event
     *
     * @return void
     */
    public function confirm(): void
    {
        $this->closeModalWithEvents([
            'modalConfirmed' => $this->message,
        ]);
    }

    /**
     * Simply close the modal
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->closeModal();
    }

    /**
     * Override the default modal width
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.test-modal');
    }
}

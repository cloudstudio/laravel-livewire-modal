<?php

namespace Cloudstudio\Modal;

use Cloudstudio\Modal\Services\ModalConfigService;
use Cloudstudio\Modal\Services\ModalEventService;
use InvalidArgumentException;
use Livewire\Component;

/**
 * Base class for Livewire modal components.
 */
abstract class LivewireModal extends Component
{
    /**
     * Whether to force close the modal.
     *
     * @var bool
     */
    public bool $forceClose = false;

    /**
     * Number of previous modals to skip when closing.
     *
     * @var int
     */
    public int $skipModals = 0;

    /**
     * Whether to destroy skipped modals.
     *
     * @var bool
     */
    public bool $destroySkipped = false;

    /**
     * Modal config service instance.
     *
     * @var ModalConfigService|null
     */
    protected ?ModalConfigService $configService = null;

    /**
     * Modal event service instance.
     *
     * @var ModalEventService|null
     */
    protected ?ModalEventService $eventService = null;

    /**
     * Get the modal config service.
     *
     * @return ModalConfigService
     */
    protected function configService(): ModalConfigService
    {
        if ($this->configService === null) {
            $this->configService = app(ModalConfigService::class);
        }

        return $this->configService;
    }

    /**
     * Get the modal event service.
     *
     * @return ModalEventService
     */
    protected function eventService(): ModalEventService
    {
        if ($this->eventService === null) {
            $this->eventService = app(ModalEventService::class);
        }

        return $this->eventService;
    }

    /**
     * Mark skipped modals for destruction.
     *
     * @return self
     */
    public function destroySkippedModals(): self
    {
        $this->destroySkipped = true;

        return $this;
    }

    /**
     * Skip previous modals.
     *
     * @param int $count Number of modals to skip
     * @param bool $destroy Whether to destroy skipped modals
     * @return self
     */
    public function skipPreviousModals(int $count = 1, bool $destroy = false): self
    {
        $this->skipPreviousModal($count, $destroy);

        return $this;
    }

    /**
     * Skip previous modal.
     *
     * @param int $count Number of modals to skip
     * @param bool $destroy Whether to destroy skipped modals
     * @return self
     */
    public function skipPreviousModal(int $count = 1, bool $destroy = false): self
    {
        $this->skipModals = $count;
        $this->destroySkipped = $destroy;

        return $this;
    }

    /**
     * Force close the modal.
     *
     * @return self
     */
    public function forceClose(): self
    {
        $this->forceClose = true;

        return $this;
    }

    /**
     * Close the modal.
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->eventService()->closeModal($this, $this->forceClose, $this->skipModals, $this->destroySkipped);
    }

    /**
     * Close the modal with events.
     *
     * @param array $events Events to emit
     * @return void
     */
    public function closeModalWithEvents(array $events): void
    {
        $this->eventService()->closeModalWithEvents($this, $events, $this->forceClose, $this->skipModals, $this->destroySkipped);
    }

    /**
     * Get the modal max width.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return app(ModalConfigService::class)->getModalMaxWidth();
    }

    /**
     * Get the modal max width CSS class.
     *
     * @return string
     * @throws InvalidArgumentException If the max width is invalid
     */
    public static function modalMaxWidthClass(): string
    {
        return app(ModalConfigService::class)->getModalMaxWidthClass(static::modalMaxWidth());
    }

    /**
     * Check if modal should close on click away.
     *
     * @return bool
     */
    public static function closeModalOnClickAway(): bool
    {
        return app(ModalConfigService::class)->shouldCloseModalOnClickAway();
    }

    /**
     * Check if modal should close on escape key.
     *
     * @return bool
     */
    public static function closeModalOnEscape(): bool
    {
        return app(ModalConfigService::class)->shouldCloseModalOnEscape();
    }

    /**
     * Check if closing modal on escape is forceful.
     *
     * @return bool
     */
    public static function closeModalOnEscapeIsForceful(): bool
    {
        return app(ModalConfigService::class)->isCloseModalOnEscapeForceful();
    }

    /**
     * Check if close event should be dispatched.
     *
     * @return bool
     */
    public static function dispatchCloseEvent(): bool
    {
        return app(ModalConfigService::class)->shouldDispatchCloseEvent();
    }

    /**
     * Check if modal should be destroyed on close.
     *
     * @return bool
     */
    public static function destroyOnClose(): bool
    {
        return app(ModalConfigService::class)->shouldDestroyOnClose();
    }
}

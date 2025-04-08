<?php

namespace Cloudstudio\Modal\Services;

use InvalidArgumentException;

/**
 * Service for handling modal configuration.
 */
class ModalConfigService
{
    /**
     * Available max width classes for modals.
     *
     * @var array<string, string>
     */
    protected static array $maxWidths = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-md md:max-w-lg',
        'xl' => 'sm:max-w-md md:max-w-xl',
        '2xl' => 'sm:max-w-md md:max-w-xl lg:max-w-2xl',
        '3xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl',
        '4xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-4xl',
        '5xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl',
        '6xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-6xl',
        '7xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-7xl',
    ];

    /**
     * Get the modal max width.
     *
     * @return string The modal max width
     */
    public function getModalMaxWidth(): string
    {
        return config('livewire-modal.component_defaults.modal_max_width', '2xl');
    }

    /**
     * Get the modal max width CSS class.
     *
     * @param  string  $maxWidth  The max width key
     * @return string The CSS class for the max width
     *
     * @throws InvalidArgumentException If the max width is invalid
     */
    public function getModalMaxWidthClass(string $maxWidth): string
    {
        if (! array_key_exists($maxWidth, static::$maxWidths)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Modal max width [%s] is invalid. The width must be one of the following [%s].',
                    $maxWidth,
                    implode(', ', array_keys(static::$maxWidths))
                ),
            );
        }

        return static::$maxWidths[$maxWidth];
    }

    /**
     * Get all available max width options.
     *
     * @return array The max width options
     */
    public function getAvailableMaxWidths(): array
    {
        return static::$maxWidths;
    }

    /**
     * Check if modal should be displayed as a flyout.
     *
     * @return bool Whether to display modal as a flyout
     */
    public function shouldDisplayAsFlyout(): bool
    {
        return config('livewire-modal.component_defaults.display_as_flyout', false);
    }

    /** 
     * Get the flyout position.
     *
     * @return string The flyout position
     */
    public function getFlyoutPosition(): string
    {
        return config('livewire-modal.component_defaults.flyout_position', 'right');
    }

    /**
     * Check if modal should close on click away.
     *
     * @return bool Whether to close modal on click away
     */
    public function shouldCloseModalOnClickAway(): bool
    {
        return config('livewire-modal.component_defaults.close_modal_on_click_away', true);
    }

    /**
     * Check if modal should close on escape key.
     *
     * @return bool Whether to close modal on escape key
     */
    public function shouldCloseModalOnEscape(): bool
    {
        return config('livewire-modal.component_defaults.close_modal_on_escape', true);
    }

    /**
     * Check if closing modal on escape is forceful.
     *
     * @return bool Whether closing modal on escape is forceful
     */
    public function isCloseModalOnEscapeForceful(): bool
    {
        return config('livewire-modal.component_defaults.close_modal_on_escape_is_forceful', true);
    }

    /**
     * Check if close event should be dispatched.
     *
     * @return bool Whether to dispatch close event
     */
    public function shouldDispatchCloseEvent(): bool
    {
        return config('livewire-modal.component_defaults.dispatch_close_event', false);
    }

    /**
     * Check if modal should be destroyed on close.
     *
     * @return bool Whether to destroy modal on close
     */
    public function shouldDestroyOnClose(): bool
    {
        return config('livewire-modal.component_defaults.destroy_on_close', false);
    }
}

<?php

namespace Cloudstudio\Modal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for accessing modal functionality.
 *
 * @method static void openModal(string $component, array $arguments = [], array $modalAttributes = [])
 * @method static void destroyComponent(string $id)
 * @method static void resetState()
 *
 * @see \Cloudstudio\Modal\ModalContainer
 */
class Modal extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Cloudstudio\Modal\ModalContainer::class;
    }
}

<?php

namespace Cloudstudio\Modal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for accessing modal functionality.
 *
 * @see \Cloudstudio\Modal\Modal
 */
class Modal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Cloudstudio\Modal\Modal::class;
    }
}

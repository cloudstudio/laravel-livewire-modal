<?php

namespace Cloudstudio\Modal;

use Illuminate\View\View;

/**
 * Clase que representa el contenedor del modal.
 *
 * Esta clase se encarga de renderizar el contenedor del modal.
 */
class ModalContainer extends Modal
{
    /**
     * Renderiza el contenedor del modal.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('laravel-livewire-modal::modal', [
            'modalScript' => __DIR__.'/../dist/modal.js',
        ]);
    }
}

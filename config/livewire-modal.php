<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modal Component Defaults
    |--------------------------------------------------------------------------
    |
    | Configure the default properties for a modal component.
    |
    | Supported modal_max_width
    | 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
    |
    | Supported flyout_position
    | 'right', 'left', 'bottom'
    */
    'component_defaults' => [
        'modal_max_width' => '2xl',

        'display_as_flyout' => false,

        'flyout_position' => 'right',

        'close_modal_on_click_away' => true,

        'close_modal_on_escape' => true,

        'close_modal_on_escape_is_forceful' => true,

        'dispatch_close_event' => false,

        'destroy_on_close' => false,
    ],
];

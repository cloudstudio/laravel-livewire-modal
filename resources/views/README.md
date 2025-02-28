# Laravel Livewire Modal Structure

This modal system is organized into a modular structure with three main components:

## 1. Modal Container (`modal.blade.php`)

The main modal container that handles the overall modal layout, transitions, and behaviors.

## 2. Modal Scripts (`modal-script.blade.php`)

Contains the JavaScript required for modal functionality.

## 3. Modal Content

Different modal content styles that can be easily swapped:

-   **Styless (`modal-content-styless.blade.php`)**: Basic modal content without styling
-   _More styles coming soon_

## Usage

To include a modal in your view:

```php
@include('laravel-livewire-modal::modal')
```

## Customization

To create a new modal content style:

1. Create a new blade file (e.g., `modal-content-custom.blade.php`)
2. Update the modal.blade.php to use your custom content:
    ```php
    @include('laravel-livewire-modal::modal-content-custom')
    ```

Or make it configurable through the Modal component class.

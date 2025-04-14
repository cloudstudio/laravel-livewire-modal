# Laravel Livewire Modal

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cloudstudio/laravel-livewire-modal.svg?style=flat-square)](https://packagist.org/packages/cloudstudio/laravel-livewire-modal)
[![Total Downloads](https://img.shields.io/packagist/dt/cloudstudio/laravel-livewire-modal.svg?style=flat-square)](https://packagist.org/packages/cloudstudio/laravel-livewire-modal)

This package is inspired by [wire-elements/modal](https://github.com/wire-elements/modal), forked and rebuilt from scratch to provide full support for Livewire v3 and Tailwind 4. It provides a powerful Livewire component that gives you a modal system that supports multiple child modals while maintaining state.

## Features

- 🚀 Fully compatible with Livewire v3
- 🎨 Styled with Tailwind 4
- 🔄 Maintains component state between modal interactions
- 📦 Support for nested/stacked modals
- 🛡️ Secure handling of data
- ⚡ Optimized performance
- 🔧 Highly customizable

## Installation

You can install the package via composer:

```bash
composer require cloudstudio/laravel-livewire-modal
```

After installing the package, you need to include the modal component in your blade layout file:

```php
<livewire:modal />
```

## Tailwind Configuration

To properly configure Tailwind 4 with this package, add these lines to your `app.css` file:

```css
@import '../../vendor/cloudstudio/laravel-livewire-modal/dist/modal.css';
```

Then run:

```bash
yarn build
```

This ensures Tailwind can properly scan and generate the necessary styles for the modal components.

## Basic Usage

### Creating a Modal Component

Create a Livewire component that extends the `LivewireModal` class:

```php
<?php

namespace App\Livewire;

use Cloudstudio\Modal\LivewireModal;
use Illuminate\View\View;

class CreateUser extends LivewireModal
{
    public $name = '';
    public $email = '';
    
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
    ];
    
    public function create()
    {
        $this->validate();
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt('password'),
        ]);
        
        $this->closeModal();
        
        // Optionally emit events when the modal is closed
        $this->dispatch('userCreated', $user->id);
    }
    
    public function render(): View
    {
        return view('livewire.create-user');
    }
}
```

### Opening a Modal

To open a modal from a Livewire component or a Blade view:

```html
<!-- From a button using onclick -->
<button onclick="Livewire.dispatch('openModal', { component: 'create-user' })">Create User</button>

<!-- From a Livewire component using wire:click -->
<button wire:click="$dispatch('openModal', { component: 'create-user' })">Create User</button>

<!-- With arguments -->
<button onclick="Livewire.dispatch('openModal', { component: 'edit-user', arguments: { user: {{ $user->id }} } })">
    Edit User
</button>
```

### Handling Arguments

You can pass arguments to your modal when opening it:

```php
public User $user;

public function mount(User $user)
{
    $this->user = $user;
    $this->name = $user->name;
    $this->email = $user->email;
}
```

### Modal Events

You can dispatch events when closing a modal:

```php
public function update()
{
    $this->validate();
    
    $this->user->update([
        'name' => $this->name,
        'email' => $this->email,
    ]);
    
    $this->closeModalWithEvents([
        'userUpdated', // Event name
        'userUpdated' => $this->user->id, // Event with data
        UserOverview::class => 'userModified', // Component event
        UserOverview::class => ['userModified', [$this->user->id]], // Component event with parameters
    ]);
}
```

## Customizing Modal Behavior

### Changing Modal Width

You can change the width of the modal by overriding the `modalMaxWidth` method:

```php
/**
 * Supported sizes: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
 */
public static function modalMaxWidth(): string
{
    return 'xl';
}
```

### Display as Flyout

To display the modal as a flyout:

```php
public static function modalFlyout(): bool
{
    return true;
}
```

### Flyout Position

To change the position of the flyout. 
Available positions are `right`, `left`, and `bottom`.
Default is `right`:

```php
public static function modalFlyoutPosition(): string
{
    return 'left';
}
```

### Disable Closing on Escape Key

To prevent the modal from closing when the escape key is pressed:

```php
public static function closeModalOnEscape(): bool
{
    return false;
}
```

### Disable Closing on Outside Click

To prevent the modal from closing when clicking outside:

```php
public static function closeModalOnClickAway(): bool
{
    return false;
}
```

### Controlling Escape Key Behavior

By default, pressing escape closes all modals. To change this behavior:

```php
public static function closeModalOnEscapeIsForceful(): bool
{
    return false;
}
```

### Triggering Close Events

To dispatch an event when the modal is closed:

```php
public static function dispatchCloseEvent(): bool
{
    return true;
}
```

### Component State Management

To destroy the component state when a modal is closed:

```php
public static function destroyOnClose(): bool
{
    return true;
}
```

## Advanced Usage

### Preventing Modal Close Based on State

You can prevent the modal from closing based on its state:

```php
@script
<script>
    $wire.on('closingModalOnEscape', data => {
        if ($wire.isDirty && !confirm('{{ __('You have unsaved changes. Are you sure you want to close this dialog?') }}')) {
            data.closing = false;
        }
    });
    $wire.on('closingModalOnClickAway', data => {
        if ($wire.isDirty && !confirm('{{ __('You have unsaved changes. Are you sure you want to close this dialog?') }}')) {
            data.closing = false;
        }
    });
</script>
@endscript
```

### Skipping Previous Modals

For nested modal workflows where you want to skip returning to certain previous modals:

```php
public function delete()
{
    // Delete logic here
    
    // Skip the previous modal and close with events
    $this->skipPreviousModal()->closeModalWithEvents([
        TeamOverview::class => 'teamDeleted'
    ]);
    
    // Or skip multiple previous modals
    // $this->skipPreviousModals(2)->closeModal();
    
    // Optionally destroy the skipped modals' state
    // $this->destroySkippedModals();
}
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=livewire-modal-config
```

This will create a `livewire-modal.php` config file with the following options:

```php
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
```

## Security

Remember to validate all data passed to your Livewire components. Since Livewire stores this information on the client-side, it can be manipulated. Use Laravel's Gate facade and other authorization mechanisms to secure your application.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Toni Soriano](https://github.com/cloudstudio)
- [All Contributors](../../contributors)
- [wire-elements/modal](https://github.com/wire-elements/modal)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


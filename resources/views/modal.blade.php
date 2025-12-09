<div>
    @include('laravel-livewire-modal::modal-script', ['modalScript' => $modalScript])

    <div x-data="LivewireUIModal()" x-on:close.stop="setShowPropertyTo(false)" wire:cloak
        x-on:keydown.escape.window="show && closeModalOnEscape()" x-show="show" class="fixed inset-0 z-20 overflow-y-auto"
        style="display: none;">

        <div class="flex items-end justify-center min-h-dvh px-4 pt-4 pb-10 text-center sm:block sm:p-0 livewire-modal-wrapper">

            <div x-show="show" x-on:click="closeModalOnClickAway()" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 transition-all transform">

                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Default Modal --}}
            <div wire:ignore.self 
                x-show="show && showActiveComponent && !modalFlyout"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-bind:class="modalWidth"
                class="relative inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full "
                id="modal-container" x-trap.noscroll="show && showActiveComponent && !modalFlyout" aria-modal="true">
                    @include('laravel-livewire-modal::modal-content', [
                        'modalFlyout' => false
                    ])
            </div>

            {{-- Flyout Right --}}
            <div wire:ignore.self 
                x-show="show && showActiveComponent && modalFlyout && modalFlyoutPosition === 'right'"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                x-bind:class="modalWidth"
                class="fixed h-full md:w-[25rem] top-0 right-0 border-s overflow-y-auto overflow-x-hidden
                z-50 shadow-2xl modal-flyout-right bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 transform transition-all text-left"
                id="modal-container-right" x-trap.noscroll="show && showActiveComponent && modalFlyout && modalFlyoutPosition === 'right'" aria-modal="true">
                    @include('laravel-livewire-modal::modal-content', [
                        'modalFlyout' => true, 
                        'modalFlyoutPosition' => 'right'
                    ])
            </div>

            {{-- Flyout Left --}}
            <div wire:ignore.self 
                x-show="show && showActiveComponent && modalFlyout && modalFlyoutPosition === 'left'"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                x-bind:class="modalWidth"
                class="fixed h-full md:w-[25rem] top-0 left-0 border-e overflow-y-auto overflow-x-hidden 
                z-50 shadow-2xl modal-flyout-left bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 transform transition-all text-left"
                id="modal-container-left" x-trap.noscroll="show && showActiveComponent && modalFlyout && modalFlyoutPosition === 'left'" aria-modal="true">
                    @include('laravel-livewire-modal::modal-content', [
                        'modalFlyout' => true, 
                        'modalFlyoutPosition' => 'left'
                    ])
            </div>

            {{-- Flyout Bottom --}}
            <div wire:ignore.self 
                x-show="show && showActiveComponent && modalFlyout && modalFlyoutPosition === 'bottom'"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-y-full"
                x-transition:enter-end="translate-y-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                x-bind:class="modalWidth"
                class="fixed min-h-[25rem] w-screen min-w-[100vw] bottom-0 left-0 right-0 border-t overflow-y-auto overflow-x-hidden
                z-50 shadow-2xl modal-flyout-bottom bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 transform transition-all text-left"
                id="modal-container-bottom" x-trap.noscroll="show && showActiveComponent && modalFlyout && modalFlyoutPosition === 'bottom'" aria-modal="true">
                    @include('laravel-livewire-modal::modal-content', [
                        'modalFlyout' => true, 
                        'modalFlyoutPosition' => 'bottom'
                    ])
            </div>
        </div>
    </div>
</div>

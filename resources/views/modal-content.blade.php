@foreach ($components as $id => $component)
    @if (Modal::shouldShowModal($component['modalAttributes'], $modalFlyout, $modalFlyoutPosition ?? null))
        <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}"
            wire:key="{{ $id }}"
            class="livewire-modal">
            @livewire($component['name'], $component['arguments'], key($id))
        </div>
    @endif
@endforeach

@unless (count($components))
    <p>Something happends... we can't show the modal.</p>
@endunless

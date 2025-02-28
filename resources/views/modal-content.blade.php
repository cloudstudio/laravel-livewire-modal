@foreach ($components as $id => $component)
    <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}"
        wire:key="{{ $id }}">
        @livewire($component['name'], $component['arguments'], key($id))
    </div>
@endforeach

@unless (count($components))
    <p>Something happends... we can't show the modal.</p>
@endunless

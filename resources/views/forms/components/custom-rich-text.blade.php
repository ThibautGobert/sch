@vite(['resources/js/filament/CustomRichText/index.js'])
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}')}"

    >
        <!-- Interact with the `state` property in Alpine.js -->
        <div @update-state.window="state = $event.detail;">
            <div class="custom-rich-text" ref="root" :data-content="state || ''"></div>
        </div>
    </div>
</x-dynamic-component>



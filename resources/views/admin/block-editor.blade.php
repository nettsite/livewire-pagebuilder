<div class="block-editor" wire:key="block-{{ $block['id'] }}">
    <div class="block-editor__header">
        <span class="block-editor__label">{{ $blockClass::label() }}</span>
        <div class="block-editor__actions">
            <button type="button"
                wire:click="moveBlockUp('{{ $block['id'] }}')"
                x-on:click="$dispatch('flush-editors')"
                aria-label="Move up">↑</button>
            <button type="button"
                wire:click="moveBlockDown('{{ $block['id'] }}')"
                x-on:click="$dispatch('flush-editors')"
                aria-label="Move down">↓</button>
            <button type="button"
                wire:click="removeBlock('{{ $block['id'] }}')"
                wire:confirm="Remove this block?"
                aria-label="Remove">×</button>
        </div>
    </div>
    <div class="block-editor__fields">
        @include($blockClass::adminView(), [
            'block' => $block,
            'wireModelPath' => $wireModelPrefix.'.data',
        ])
    </div>
    @if ($blockClass::allowedChildTypes())
        @include('livewire-pagebuilder::admin.child-block-editor', [
            'block' => $block,
            'blockClass' => $blockClass,
            'wireModelPrefix' => $wireModelPrefix,
        ])
    @endif
</div>

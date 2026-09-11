<div class="child-block-editor">
    @foreach ($block['children'] ?? [] as $childIndex => $child)
        @php $childClass = app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->find($child['type']); @endphp
        @if ($childClass)
            <div class="block-editor block-editor--child" wire:key="child-{{ $child['id'] }}">
                <div class="block-editor__header">
                    <span class="block-editor__label">{{ $childClass::label() }}</span>
                    <div class="block-editor__actions">
                        <button type="button"
                            wire:click="moveChildBlockUp('{{ $block['id'] }}', '{{ $child['id'] }}')"
                            x-on:click="$dispatch('flush-editors')"
                            aria-label="Move up">↑</button>
                        <button type="button"
                            wire:click="moveChildBlockDown('{{ $block['id'] }}', '{{ $child['id'] }}')"
                            x-on:click="$dispatch('flush-editors')"
                            aria-label="Move down">↓</button>
                        <button type="button"
                            wire:click="removeChildBlock('{{ $block['id'] }}', '{{ $child['id'] }}')"
                            wire:confirm="Remove this block?"
                            aria-label="Remove">×</button>
                    </div>
                </div>
                <div class="block-editor__fields">
                    @include($childClass::adminView(), [
                        'block' => $child,
                        'wireModelPath' => $wireModelPrefix.'.children.'.$childIndex.'.data',
                    ])
                </div>
            </div>
        @endif
    @endforeach

    <div class="child-block-editor__add">
        @foreach ($blockClass::allowedChildTypes() as $type)
            @php $childClass = app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->find($type); @endphp
            @if ($childClass)
                <button type="button" wire:click="addChildBlock('{{ $block['id'] }}', '{{ $type }}')">
                    + {{ $childClass::label() }}
                </button>
            @endif
        @endforeach
    </div>
</div>

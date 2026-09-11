@foreach ($blocks as $index => $block)
    @php $blockClass = app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->find($block['type']); @endphp
    @if ($blockClass)
        @include('livewire-pagebuilder::admin.block-editor', [
            'block' => $block,
            'blockClass' => $blockClass,
            'index' => $index,
            'wireModelPrefix' => 'blocks.'.$index,
        ])
    @endif
@endforeach

<div class="block-list__add">
    @foreach (app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->all() as $type => $class)
        <button type="button" wire:click="addBlock('{{ $type }}')">
            + {{ $class::label() }}
        </button>
    @endforeach
</div>

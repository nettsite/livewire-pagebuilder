@foreach ($blocks as $block)
    @php $blockClass = app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->find($block['type']); @endphp
    @if ($blockClass)
        @include($blockClass::frontendView(), ['block' => $block])
    @endif
@endforeach

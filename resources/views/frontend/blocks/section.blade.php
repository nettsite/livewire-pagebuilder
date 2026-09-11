<section class="section section--{{ $block['data']['background'] ?? 'white' }}">
    <div class="section__inner">
        @foreach ($block['children'] ?? [] as $child)
            @php $childClass = app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->find($child['type']); @endphp
            @if ($childClass)
                @include($childClass::frontendView(), ['block' => $child])
            @endif
        @endforeach
    </div>
</section>

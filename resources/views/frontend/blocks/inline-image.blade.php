<figure class="inline-image inline-image--{{ $block['data']['size'] ?? 'full' }}">
    @if (!empty($block['data']['url']))
        <img src="{{ $block['data']['url'] }}"
             alt="{{ $block['data']['alt'] ?? '' }}"
             class="inline-image__img"
             loading="lazy">
    @endif
    @if (!empty($block['data']['caption']))
        <figcaption class="inline-image__caption">{{ $block['data']['caption'] }}</figcaption>
    @endif
</figure>

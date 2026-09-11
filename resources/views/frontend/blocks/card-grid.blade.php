<div class="card-grid card-grid--columns-{{ $block['data']['columns'] ?? 3 }}">
    @foreach ($block['data']['cards'] ?? [] as $card)
        <div class="card-grid__card">
            @if (!empty($card['title']))
                <h3 class="card-grid__card-title">{{ $card['title'] }}</h3>
            @endif
            @if (!empty($card['body']))
                <div class="card-grid__card-body">{{ $card['body'] }}</div>
            @endif
            @if (!empty($card['link_url']))
                <a class="card-grid__card-link" href="{{ $card['link_url'] }}">Learn more</a>
            @endif
        </div>
    @endforeach
</div>

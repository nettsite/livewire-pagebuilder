<section class="hero" aria-label="Hero">
    @if (!empty($block['data']['image_url']))
        <img class="hero__bg" src="{{ $block['data']['image_url'] }}" alt="" loading="eager">
        <div class="hero__overlay" aria-hidden="true"></div>
    @endif
    <div class="hero__content">
        <h1>{{ $block['data']['heading'] ?? '' }}</h1>
        @if (!empty($block['data']['subheading']))
            <p class="hero__subheading">{{ $block['data']['subheading'] }}</p>
        @endif
        @if (!empty($block['data']['cta_text']))
            <a class="btn" href="{{ $block['data']['cta_url'] ?? '#' }}">{{ $block['data']['cta_text'] }}</a>
        @endif
    </div>
</section>

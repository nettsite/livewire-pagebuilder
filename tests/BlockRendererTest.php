<?php

use NettSite\LivewirePagebuilder\BlockRegistry;
use NettSite\LivewirePagebuilder\Blocks\HeroBlock;

it('renders the correct frontend partial for a registered block type', function () {
    $registry = app(BlockRegistry::class);
    $registry->register(HeroBlock::class);

    $block = $registry->make('hero');
    $block['data']['heading'] = 'Hello World';

    $html = view('livewire-pagebuilder::frontend.blocks.hero', ['block' => $block])->render();

    expect($html)->toContain('Hello World');
    expect($html)->toContain('hero');
});

it('skips unknown block types silently in the renderer component', function () {
    $blocks = [
        ['id' => 'abc', 'type' => 'nonexistent', 'data' => []],
    ];

    $html = view('livewire-pagebuilder::components.renderer', ['blocks' => $blocks])->render();

    expect(trim($html))->toBe('');
});

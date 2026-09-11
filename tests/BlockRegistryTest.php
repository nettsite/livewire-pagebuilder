<?php

use NettSite\LivewirePagebuilder\BlockRegistry;
use NettSite\LivewirePagebuilder\Blocks\HeroBlock;
use NettSite\LivewirePagebuilder\Blocks\RichTextBlock;

it('registers a block class', function () {
    $registry = new BlockRegistry;
    $registry->register(HeroBlock::class);

    expect($registry->all())->toHaveKey('hero', HeroBlock::class);
});

it('finds a registered block class by type', function () {
    $registry = new BlockRegistry;
    $registry->register(HeroBlock::class);

    expect($registry->find('hero'))->toBe(HeroBlock::class);
});

it('returns null for unknown type', function () {
    $registry = new BlockRegistry;

    expect($registry->find('unknown'))->toBeNull();
});

it('makes a block array with id, type, and default data', function () {
    $registry = new BlockRegistry;
    $registry->register(HeroBlock::class);

    $block = $registry->make('hero');

    expect($block)
        ->toHaveKey('id')
        ->toHaveKey('type', 'hero')
        ->toHaveKey('data');

    expect($block['data'])->toMatchArray(HeroBlock::defaultData());
    expect($block['id'])->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-/');
});

it('throws for unknown type in make', function () {
    $registry = new BlockRegistry;

    expect(fn () => $registry->make('unknown'))
        ->toThrow(InvalidArgumentException::class);
});

it('registers multiple block types', function () {
    $registry = new BlockRegistry;
    $registry->register(HeroBlock::class);
    $registry->register(RichTextBlock::class);

    expect($registry->all())->toHaveKeys(['hero', 'rich-text']);
});

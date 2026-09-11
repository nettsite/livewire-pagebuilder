<?php

use NettSite\LivewirePagebuilder\BlockRegistry;
use NettSite\LivewirePagebuilder\Blocks\CardGridBlock;
use NettSite\LivewirePagebuilder\Blocks\HeroBlock;
use NettSite\LivewirePagebuilder\Blocks\RichTextBlock;
use NettSite\LivewirePagebuilder\Blocks\SectionBlock;
use NettSite\LivewirePagebuilder\Concerns\ManagesBlocks;

function makeTestComponent(): object
{
    return new class
    {
        use ManagesBlocks;

        public array $blocks = [];
    };
}

beforeEach(function () {
    $registry = app(BlockRegistry::class);
    $registry->register(HeroBlock::class);
    $registry->register(RichTextBlock::class);
    $registry->register(SectionBlock::class);
    $registry->register(CardGridBlock::class);
});

it('addBlock appends a block with correct structure', function () {
    $component = makeTestComponent();
    $component->addBlock('hero');

    expect($component->blocks)->toHaveCount(1);
    expect($component->blocks[0])->toHaveKeys(['id', 'type', 'data']);
    expect($component->blocks[0]['type'])->toBe('hero');
});

it('removeBlock removes the block by id', function () {
    $component = makeTestComponent();
    $component->addBlock('hero');
    $component->addBlock('rich-text');

    $id = $component->blocks[0]['id'];
    $component->removeBlock($id);

    expect($component->blocks)->toHaveCount(1);
    expect($component->blocks[0]['type'])->toBe('rich-text');
    expect(array_keys($component->blocks))->toBe([0]);
});

it('moveBlockUp swaps block with the one before it', function () {
    $component = makeTestComponent();
    $component->addBlock('hero');
    $component->addBlock('rich-text');

    $id = $component->blocks[1]['id'];
    $component->moveBlockUp($id);

    expect($component->blocks[0]['type'])->toBe('rich-text');
    expect($component->blocks[1]['type'])->toBe('hero');
});

it('moveBlockUp does nothing when already first', function () {
    $component = makeTestComponent();
    $component->addBlock('hero');
    $component->addBlock('rich-text');

    $id = $component->blocks[0]['id'];
    $component->moveBlockUp($id);

    expect($component->blocks[0]['type'])->toBe('hero');
});

it('moveBlockDown swaps block with the one after it', function () {
    $component = makeTestComponent();
    $component->addBlock('hero');
    $component->addBlock('rich-text');

    $id = $component->blocks[0]['id'];
    $component->moveBlockDown($id);

    expect($component->blocks[0]['type'])->toBe('rich-text');
    expect($component->blocks[1]['type'])->toBe('hero');
});

it('moveBlockDown does nothing when already last', function () {
    $component = makeTestComponent();
    $component->addBlock('hero');
    $component->addBlock('rich-text');

    $id = $component->blocks[1]['id'];
    $component->moveBlockDown($id);

    expect($component->blocks[1]['type'])->toBe('rich-text');
});

it('addChildBlock appends a child to a section block', function () {
    $component = makeTestComponent();
    $component->addBlock('section');

    $parentId = $component->blocks[0]['id'];
    $component->addChildBlock($parentId, 'rich-text');

    expect($component->blocks[0]['children'])->toHaveCount(1);
    expect($component->blocks[0]['children'][0]['type'])->toBe('rich-text');
});

it('removeChildBlock removes child by id', function () {
    $component = makeTestComponent();
    $component->addBlock('section');
    $parentId = $component->blocks[0]['id'];
    $component->addChildBlock($parentId, 'rich-text');
    $component->addChildBlock($parentId, 'rich-text');

    $childId = $component->blocks[0]['children'][0]['id'];
    $component->removeChildBlock($parentId, $childId);

    expect($component->blocks[0]['children'])->toHaveCount(1);
    expect(array_keys($component->blocks[0]['children']))->toBe([0]);
});

it('addSubItem appends sub-item to data key', function () {
    $component = makeTestComponent();
    $component->addBlock('card-grid');

    $blockId = $component->blocks[0]['id'];
    $component->addSubItem($blockId, 'cards', ['title' => 'Test', 'body' => '', 'link_url' => '']);

    expect($component->blocks[0]['data']['cards'])->toHaveCount(1);
    expect($component->blocks[0]['data']['cards'][0]['title'])->toBe('Test');
});

it('removeSubItem removes sub-item by index', function () {
    $component = makeTestComponent();
    $component->addBlock('card-grid');
    $blockId = $component->blocks[0]['id'];
    $component->addSubItem($blockId, 'cards', ['title' => 'First', 'body' => '', 'link_url' => '']);
    $component->addSubItem($blockId, 'cards', ['title' => 'Second', 'body' => '', 'link_url' => '']);

    $component->removeSubItem($blockId, 'cards', 0);

    expect($component->blocks[0]['data']['cards'])->toHaveCount(1);
    expect($component->blocks[0]['data']['cards'][0]['title'])->toBe('Second');
});

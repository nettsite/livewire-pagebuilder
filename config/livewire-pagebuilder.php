<?php

use NettSite\LivewirePagebuilder\Blocks\CardGridBlock;
use NettSite\LivewirePagebuilder\Blocks\HeroBlock;
use NettSite\LivewirePagebuilder\Blocks\InlineImageBlock;
use NettSite\LivewirePagebuilder\Blocks\RichTextBlock;
use NettSite\LivewirePagebuilder\Blocks\SectionBlock;
use NettSite\LivewirePagebuilder\Blocks\TwoColumnBlock;

// config for NettSite/LivewirePagebuilder
return [
    'blocks' => [
        HeroBlock::class,
        RichTextBlock::class,
        TwoColumnBlock::class,
        CardGridBlock::class,
        InlineImageBlock::class,
        SectionBlock::class,
    ],
];

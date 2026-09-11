<?php

namespace NettSite\LivewirePagebuilder\Blocks;

use NettSite\LivewirePagebuilder\Block;

class HeroBlock extends Block
{
    public static function type(): string
    {
        return 'hero';
    }

    public static function label(): string
    {
        return 'Hero';
    }

    public static function defaultData(): array
    {
        return [
            'heading' => '',
            'subheading' => '',
            'cta_text' => '',
            'cta_url' => '',
            'image_url' => '',
        ];
    }
}

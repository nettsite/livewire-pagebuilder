<?php

namespace NettSite\LivewirePagebuilder\Blocks;

use NettSite\LivewirePagebuilder\Block;

class InlineImageBlock extends Block
{
    public static function type(): string
    {
        return 'inline-image';
    }

    public static function label(): string
    {
        return 'Inline Image';
    }

    public static function defaultData(): array
    {
        return [
            'url' => '',
            'alt' => '',
            'caption' => '',
            'size' => 'full',
        ];
    }
}

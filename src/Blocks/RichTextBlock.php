<?php

namespace NettSite\LivewirePagebuilder\Blocks;

use NettSite\LivewirePagebuilder\Block;

class RichTextBlock extends Block
{
    public static function type(): string
    {
        return 'rich-text';
    }

    public static function label(): string
    {
        return 'Rich Text';
    }

    public static function defaultData(): array
    {
        return [
            'content' => '',
        ];
    }
}

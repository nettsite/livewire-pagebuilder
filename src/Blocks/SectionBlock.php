<?php

namespace NettSite\LivewirePagebuilder\Blocks;

use NettSite\LivewirePagebuilder\Block;

class SectionBlock extends Block
{
    public static function type(): string
    {
        return 'section';
    }

    public static function label(): string
    {
        return 'Section';
    }

    public static function defaultData(): array
    {
        return [
            'background' => 'white',
        ];
    }

    public static function allowedChildTypes(): array
    {
        return ['rich-text', 'card-grid', 'inline-image'];
    }
}

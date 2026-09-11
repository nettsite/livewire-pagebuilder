<?php

namespace NettSite\LivewirePagebuilder\Blocks;

use NettSite\LivewirePagebuilder\Block;

class TwoColumnBlock extends Block
{
    public static function type(): string
    {
        return 'two-column';
    }

    public static function label(): string
    {
        return 'Two Column';
    }

    public static function defaultData(): array
    {
        return [
            'left' => '',
            'right' => '',
        ];
    }
}

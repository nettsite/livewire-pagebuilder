<?php

namespace NettSite\LivewirePagebuilder\Blocks;

use NettSite\LivewirePagebuilder\Block;

class CardGridBlock extends Block
{
    public static function type(): string
    {
        return 'card-grid';
    }

    public static function label(): string
    {
        return 'Card Grid';
    }

    public static function defaultData(): array
    {
        return [
            'columns' => 3,
            'cards' => [],
        ];
    }
}

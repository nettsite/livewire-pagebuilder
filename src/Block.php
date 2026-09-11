<?php

namespace NettSite\LivewirePagebuilder;

abstract class Block
{
    abstract public static function type(): string;

    abstract public static function label(): string;

    abstract public static function defaultData(): array;

    public static function adminView(): string
    {
        return 'livewire-pagebuilder::admin.blocks.'.static::type();
    }

    public static function frontendView(): string
    {
        return 'livewire-pagebuilder::frontend.blocks.'.static::type();
    }

    /** Non-empty = container block; lists allowed child block types */
    public static function allowedChildTypes(): array
    {
        return [];
    }
}

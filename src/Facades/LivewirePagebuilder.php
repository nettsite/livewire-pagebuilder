<?php

namespace NettSite\LivewirePagebuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NettSite\LivewirePagebuilder\LivewirePagebuilder
 */
class LivewirePagebuilder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \NettSite\LivewirePagebuilder\LivewirePagebuilder::class;
    }
}

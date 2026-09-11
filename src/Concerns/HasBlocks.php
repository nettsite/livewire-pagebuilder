<?php

namespace NettSite\LivewirePagebuilder\Concerns;

trait HasBlocks
{
    public function initializeHasBlocks(): void
    {
        $this->casts['blocks'] = 'array';
    }
}

<?php

namespace NettSite\LivewirePagebuilder\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BlockRenderer extends Component
{
    public function __construct(public array $blocks = []) {}

    public function render(): View
    {
        return view('livewire-pagebuilder::components.renderer');
    }
}

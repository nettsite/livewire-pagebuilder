<?php

namespace NettSite\LivewirePagebuilder;

use Illuminate\Support\Facades\Blade;
use NettSite\LivewirePagebuilder\Components\BlockRenderer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LivewirePagebuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-pagebuilder')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BlockRegistry::class);
    }

    public function packageBooted(): void
    {
        $registry = $this->app->make(BlockRegistry::class);

        foreach (config('livewire-pagebuilder.blocks', []) as $class) {
            $registry->register($class);
        }

        Blade::component('livewire-pagebuilder::renderer', BlockRenderer::class);
    }
}

<?php

namespace NettSite\LivewirePagebuilder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use NettSite\LivewirePagebuilder\Commands\LivewirePagebuilderCommand;

class LivewirePagebuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-pagebuilder')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_livewire_pagebuilder_table')
            ->hasCommand(LivewirePagebuilderCommand::class);
    }
}

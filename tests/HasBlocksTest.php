<?php

use Illuminate\Database\Eloquent\Model;
use NettSite\LivewirePagebuilder\Concerns\HasBlocks;

it('casts blocks column to array on a model', function () {
    $model = new class extends Model
    {
        use HasBlocks;

        protected $guarded = [];
    };

    // initializeHasBlocks adds the cast — simulate it
    $model->initializeHasBlocks();

    expect($model->getCasts())->toHaveKey('blocks', 'array');
});

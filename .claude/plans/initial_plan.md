+# nettsite/livewire-pagebuilder — Package Plan

## Context

A reusable Laravel package providing block-based page building primitives for any Laravel + Livewire app. Does NOT assume static site generation, Flux UI, or any specific CSS framework. Views are publishable for app-level customisation. The nettsite website is the first consumer.

**Namespace:** `NettSite\LivewirePagebuilder`
**Package name:** `nettsite/livewire-pagebuilder`
**Skeleton:** Already configured (Spatie package skeleton via `configure.php`)

---

## Package Architecture

```
src/
  Block.php                              # Abstract base block class
  BlockRegistry.php                      # Singleton, registered in provider
  Concerns/
    ManagesBlocks.php                    # Livewire component trait (block mutations)
    HasBlocks.php                        # Eloquent model trait (cast)
  Blocks/                                # Built-in block types
    HeroBlock.php
    RichTextBlock.php
    TwoColumnBlock.php
    CardGridBlock.php
    InlineImageBlock.php
    SectionBlock.php
  Components/
    BlockRenderer.php                    # Blade component (renders blocks array)
  LivewirePagebuilderServiceProvider.php

resources/views/
  components/
    renderer.blade.php                   # loops blocks → includes type partial
  admin/
    block-list.blade.php                 # outer loop + "add block" buttons
    block-editor.blade.php               # single block: header + type partial
    child-block-editor.blade.php         # same for section children
    blocks/
      hero.blade.php
      rich-text.blade.php
      two-column.blade.php
      card-grid.blade.php
      inline-image.blade.php
      section.blade.php
  frontend/
    blocks/
      hero.blade.php
      rich-text.blade.php
      two-column.blade.php
      card-grid.blade.php
      inline-image.blade.php
      section.blade.php

config/
  livewire-pagebuilder.php
```

---

## 1. Cleanup skeleton files

- Delete `src/Skeleton.php` → replace class file with `src/LivewirePagebuilder.php` (already done by configure.php)
- Delete or repurpose `src/Commands/LivewirePagebuilderCommand.php` (not needed yet)
- Remove command from service provider

---

## 2. `src/Block.php` — Abstract base class

```php
abstract class Block
{
    abstract public static function type(): string;
    abstract public static function label(): string;
    abstract public static function defaultData(): array;

    public static function adminView(): string {
        return 'livewire-pagebuilder::admin.blocks.' . static::type();
    }

    public static function frontendView(): string {
        return 'livewire-pagebuilder::frontend.blocks.' . static::type();
    }

    /** Non-empty = container block; lists allowed child block types */
    public static function allowedChildTypes(): array {
        return [];
    }
}
```

---

## 3. Built-in block classes (`src/Blocks/`)

| Class | type() | defaultData() keys | allowedChildTypes() |
|-------|--------|--------------------|---------------------|
| `HeroBlock` | `hero` | heading, subheading, cta_text, cta_url, image_url | [] |
| `RichTextBlock` | `rich-text` | content | [] |
| `TwoColumnBlock` | `two-column` | left, right | [] |
| `CardGridBlock` | `card-grid` | columns (3), cards ([]) | [] |
| `InlineImageBlock` | `inline-image` | url, alt, caption, size ('full') | [] |
| `SectionBlock` | `section` | background ('white') | ['rich-text', 'card-grid', 'inline-image'] |

---

## 4. `src/BlockRegistry.php`

```php
class BlockRegistry
{
    /** @var array<string, class-string<Block>> */
    private array $types = [];

    public function register(string $blockClass): static;
    public function find(string $type): ?string;   // returns FQCN or null
    public function all(): array;                  // type => FQCN map
    public function make(string $type): array;     // ['id' => uuid4, 'type' => ..., 'data' => defaultData()]
}
```

Registered as singleton in service provider. `make()` uses `Str::uuid()`.

---

## 5. `src/Concerns/HasBlocks.php` — Eloquent trait

```php
trait HasBlocks
{
    public function initializeHasBlocks(): void
    {
        $this->casts['blocks'] = 'array';
    }
}
```

Apps add the `blocks` JSON column via their own migration (not the package's responsibility).

---

## 6. `src/Concerns/ManagesBlocks.php` — Livewire trait

Consuming Livewire component must declare `public array $blocks = []`.

```php
trait ManagesBlocks
{
    public function addBlock(string $type): void;
    public function removeBlock(string $id): void;
    public function moveBlockUp(string $id): void;
    public function moveBlockDown(string $id): void;

    // Section children (only for container blocks)
    public function addChildBlock(string $parentId, string $type): void;
    public function removeChildBlock(string $parentId, string $childId): void;
    public function moveChildBlockUp(string $parentId, string $childId): void;
    public function moveChildBlockDown(string $parentId, string $childId): void;

    // Generic repeatable sub-item (e.g. cards in card-grid)
    public function addSubItem(string $blockId, string $dataKey, array $defaults = []): void;
    public function removeSubItem(string $blockId, string $dataKey, int $itemIndex): void;

    private function findBlockIndex(string $id): int;
    private function findChildIndex(string $parentId, string $childId): int;
}
```

All mutation methods must end with `$this->blocks = array_values($this->blocks)` to keep array keys sequential. Uses `app(BlockRegistry::class)->make($type)` to create new blocks.

---

## 7. `src/Components/BlockRenderer.php` — Blade component

```php
class BlockRenderer extends Component
{
    public function __construct(public array $blocks = []) {}

    public function render(): View
    {
        return view('livewire-pagebuilder::components.renderer');
    }
}
```

`renderer.blade.php`:
```blade
@foreach ($blocks as $block)
    @php $blockClass = app(\NettSite\LivewirePagebuilder\BlockRegistry::class)->find($block['type']); @endphp
    @if ($blockClass)
        @include($blockClass::frontendView(), ['block' => $block])
    @endif
@endforeach
```

Usage: `<x-livewire-pagebuilder::renderer :blocks="$page->blocks ?? []" />`

---

## 8. `src/LivewirePagebuilderServiceProvider.php`

```php
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
```

---

## 9. `config/livewire-pagebuilder.php`

```php
return [
    'blocks' => [
        \NettSite\LivewirePagebuilder\Blocks\HeroBlock::class,
        \NettSite\LivewirePagebuilder\Blocks\RichTextBlock::class,
        \NettSite\LivewirePagebuilder\Blocks\TwoColumnBlock::class,
        \NettSite\LivewirePagebuilder\Blocks\CardGridBlock::class,
        \NettSite\LivewirePagebuilder\Blocks\InlineImageBlock::class,
        \NettSite\LivewirePagebuilder\Blocks\SectionBlock::class,
    ],
];
```

---

## 10. `composer.json` additions

```json
"require": {
    "php": "^8.4",
    "spatie/laravel-package-tools": "^1.16",
    "illuminate/contracts": "^11.0||^12.0||^13.0",
    "livewire/livewire": "^4.0"
}
```

---

## 11. Admin views (generic HTML — no Flux UI)

`admin/block-editor.blade.php`:
- Outer `div` with `wire:key="block-{{ $block['id'] }}"`
- Header row: type label + up/down buttons (each dispatches `flush-editors` JS event) + delete (`wire:confirm`)
- `@include` to type-specific partial: `$blockClass::adminView()`

Rich-text and two-column admin partials output:
```blade
<div x-data="ckeditor('{{ $wireModelPath }}')" wire:ignore>
    <div x-ref="editor"></div>
</div>
```
Consuming app must register an `Alpine.data('ckeditor', ...)` component. Documented in README.

All other block partials use generic `<input>`, `<textarea>`, `<select>` with `wire:model`.

---

## 12. Frontend views (semantic BEM HTML)

Output semantic HTML with BEM class names. No framework assumptions. Apps publish and override to match their CSS. Guard all data access with `?? ''` / `?? []`.

Example `frontend/blocks/hero.blade.php`:
```blade
<section class="hero" aria-label="Hero">
    @if (!empty($block['data']['image_url']))
        <img class="hero__bg" src="{{ $block['data']['image_url'] }}" alt="" loading="eager">
        <div class="hero__overlay" aria-hidden="true"></div>
    @endif
    <div class="hero__content">
        <h1>{{ $block['data']['heading'] ?? '' }}</h1>
        @if (!empty($block['data']['subheading']))
            <p>{{ $block['data']['subheading'] }}</p>
        @endif
        @if (!empty($block['data']['cta_text']))
            <a class="btn" href="{{ $block['data']['cta_url'] ?? '#' }}">{{ $block['data']['cta_text'] }}</a>
        @endif
    </div>
</section>
```

`frontend/blocks/section.blade.php` recurses via `@include` loop over `$block['children']`.

---

## 13. Tests

**`tests/BlockRegistryTest.php`:**
- `register()` adds to types map
- `find()` returns correct class
- `make()` returns array with `id`, `type`, `data`; unknown type throws

**`tests/ManagesBlocksTest.php`** (stub Livewire component via `make()`):
- `addBlock` appends block with correct structure
- `removeBlock` removes by ID, re-indexes
- `moveBlockUp` / `moveBlockDown` swaps correctly at boundaries
- `addChildBlock` / `removeChildBlock` on section blocks
- `addSubItem` / `removeSubItem` on card-grid data.cards

**`tests/HasBlocksTest.php`:**
- Blocks column cast as array on stub model

**`tests/BlockRendererTest.php`:**
- Renders correct partial per block type
- Skips unknown block types silently

---

## Implementation Order

1. `composer.json` — add `livewire/livewire` require, run `composer install`
2. Remove/repurpose skeleton command
3. `Block.php` abstract class
4. All 6 block classes (`src/Blocks/`)
5. `BlockRegistry.php`
6. `HasBlocks.php` trait
7. `ManagesBlocks.php` trait
8. `BlockRenderer.php` Blade component
9. Service provider wiring (singleton + boot registration)
10. Config file
11. Generic admin view partials (all 6 block types)
12. Frontend view partials (all 6 block types)
13. Tests
14. `composer test` — all green
15. `composer analyse` — PHPStan clean
16. `composer format` — Pint clean

---

## Critical Files

- `src/Block.php`
- `src/BlockRegistry.php`
- `src/Concerns/ManagesBlocks.php` (most complex)
- `src/LivewirePagebuilderServiceProvider.php`
- `config/livewire-pagebuilder.php`
- `resources/views/admin/block-editor.blade.php`
- `resources/views/frontend/blocks/section.blade.php` (recursive)

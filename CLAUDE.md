# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
composer test              # Run Pest test suite
composer test-coverage     # Run tests with coverage
composer analyse           # PHPStan static analysis (level 5)
composer format            # Laravel Pint code style fixer
```

Run a single test:
```bash
vendor/bin/pest tests/ExampleTest.php
vendor/bin/pest --filter "test name"
```

## Architecture

**Package type:** Laravel package built on `spatie/laravel-package-tools`. Namespace: `NettSite\LivewirePagebuilder`.

**Purpose:** Block-based page building primitives for any Laravel + Livewire app. No static site generation, Flux UI, or CSS framework assumptions. Views are publishable for app-level customisation.

### Block data structure

Every block in the `$blocks` array has this shape (produced by `BlockRegistry::make()`):

```php
[
    'id'   => 'uuid-v4-string',
    'type' => 'hero',           // matches Block::type()
    'data' => [...],            // from Block::defaultData()
    // container blocks only:
    'children' => [             // same shape, nested
        ['id' => ..., 'type' => ..., 'data' => ...],
    ],
]
```

Sub-items (e.g. cards inside `card-grid`) live in `data['cards']`, not in `children`. `children` is only used by container blocks (`SectionBlock`).

### Core classes

- `src/Block.php` — Abstract base. Subclasses implement `type()`, `label()`, `defaultData()`. Provides `adminView()`, `frontendView()`, and `allowedChildTypes()`.
- `src/BlockRegistry.php` — Singleton. `register(class)`, `find(type)`, `all()`, `make(type)` (returns block array with UUID).
- `src/Concerns/HasBlocks.php` — Eloquent trait. Casts `blocks` column to array.
- `src/Concerns/ManagesBlocks.php` — Livewire trait. Block mutation methods. **Consuming component must declare `public array $blocks = []`.**
- `src/Components/BlockRenderer.php` — Blade component. Usage: `<x-livewire-pagebuilder::renderer :blocks="$page->blocks ?? []" />`.
- `src/LivewirePagebuilderServiceProvider.php` — Registers singleton, boots block registry from config, registers Blade component.

### Built-in block types (`src/Blocks/`)

| Class | type() | Container? |
|-------|--------|-----------|
| `HeroBlock` | `hero` | No |
| `RichTextBlock` | `rich-text` | No |
| `TwoColumnBlock` | `two-column` | No |
| `CardGridBlock` | `card-grid` | No (has sub-items: `cards`) |
| `InlineImageBlock` | `inline-image` | No |
| `SectionBlock` | `section` | Yes — children: `rich-text`, `card-grid`, `inline-image` |

### Views (`resources/views/`)

```
components/renderer.blade.php          # loops blocks → frontendView() partials
admin/
  block-list.blade.php                 # outer loop + "add block" buttons
  block-editor.blade.php               # single block: header + type partial
  child-block-editor.blade.php         # same for section children
  blocks/{hero,rich-text,...}.blade.php
frontend/
  blocks/{hero,rich-text,...}.blade.php  # semantic BEM HTML, no framework assumptions
```

View naming is derived from `type()`: `adminView()` → `livewire-pagebuilder::admin.blocks.{type}`, `frontendView()` → `livewire-pagebuilder::frontend.blocks.{type}`.

Rich-text and two-column admin partials use Alpine.js `ckeditor` component (`x-data="ckeditor(...)"` + `wire:ignore`). The consuming app must register `Alpine.data('ckeditor', ...)`.

Frontend views use BEM class names and guard all data with `?? ''` / `?? []`.

### Config (`config/livewire-pagebuilder.php`)

```php
return [
    'blocks' => [
        \NettSite\LivewirePagebuilder\Blocks\HeroBlock::class,
        // ... all 6 built-in block classes
    ],
];
```

Register custom block classes here. Each must extend `Block`.

### Adding a new block type

1. Create `src/Blocks/MyBlock.php` extending `Block` — implement `type()`, `label()`, `defaultData()`. Override `allowedChildTypes()` if it should be a container.
2. Add the class to `config/livewire-pagebuilder.php` → `blocks` array.
3. Create `resources/views/admin/blocks/{type}.blade.php` (editor partial).
4. Create `resources/views/frontend/blocks/{type}.blade.php` (render partial).
5. If it's a container, also update `SectionBlock::allowedChildTypes()` if children should be nestable inside sections.

### Config & migration

- Config published as `config/livewire-pagebuilder.php`.
- No package migration — consuming app adds its own `blocks` JSON column: `$table->json('blocks')->nullable()`.

### Testing

Uses [Orchestra Testbench](https://github.com/orchestral/testbench). `TestCase` registers `LivewirePagebuilderServiceProvider`. Tests use in-memory SQLite (`database.default = 'testing'`). No migrations run by default — add inline schema setup in individual tests when a real table is needed.

### Static analysis

PHPStan at level 5. Baseline in `phpstan-baseline.neon`. Source paths: `src/`, `config/`, `database/`.

### CI workflows (`.github/workflows/`)

- `run-tests.yml` — Pest on multiple PHP/Laravel versions
- `phpstan.yml` — static analysis
- `fix-php-code-style-issues.yml` — Pint auto-fix
- `update-changelog.yml` — auto-updates CHANGELOG on release

# nettsite/livewire-pagebuilder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nettsite/livewire-pagebuilder.svg?style=flat-square)](https://packagist.org/packages/nettsite/livewire-pagebuilder)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nettsite/livewire-pagebuilder/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nettsite/livewire-pagebuilder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nettsite/livewire-pagebuilder/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nettsite/livewire-pagebuilder/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nettsite/livewire-pagebuilder.svg?style=flat-square)](https://packagist.org/packages/nettsite/livewire-pagebuilder)

Block-based page building primitives for Laravel + Livewire apps. Framework-agnostic — no Flux UI, no Tailwind assumptions. Views are publishable and fully overridable.

## Requirements

- PHP 8.4+
- Laravel 11 or 12
- Livewire 4

## Installation

```bash
composer require nettsite/livewire-pagebuilder
```

Publish the config:

```bash
php artisan vendor:publish --tag="livewire-pagebuilder-config"
```

Publish views (optional — only if you need to customise templates):

```bash
php artisan vendor:publish --tag="livewire-pagebuilder-views"
```

Add a `blocks` JSON column to any model you want to attach page content to:

```php
$table->json('blocks')->nullable();
```

## Usage

### 1. Add `HasBlocks` to your model

```php
use NettSite\LivewirePagebuilder\Concerns\HasBlocks;

class Page extends Model
{
    use HasBlocks;

    protected $fillable = ['title', 'blocks'];
}
```

This casts the `blocks` column to an array automatically.

### 2. Add `ManagesBlocks` to your Livewire component

```php
use Livewire\Component;
use NettSite\LivewirePagebuilder\Concerns\ManagesBlocks;

class PageEditor extends Component
{
    use ManagesBlocks;

    public array $blocks = [];

    public function mount(Page $page): void
    {
        $this->blocks = $page->blocks ?? [];
    }

    public function save(): void
    {
        $this->page->update(['blocks' => $this->blocks]);
    }
}
```

Available mutation methods:

```php
$this->addBlock('hero');
$this->removeBlock($id);
$this->moveBlockUp($id);
$this->moveBlockDown($id);

// Section (container block) children
$this->addChildBlock($parentId, 'rich-text');
$this->removeChildBlock($parentId, $childId);
$this->moveChildBlockUp($parentId, $childId);
$this->moveChildBlockDown($parentId, $childId);

// Repeatable sub-items (e.g. cards inside card-grid)
$this->addSubItem($blockId, 'cards', ['title' => '', 'body' => '']);
$this->removeSubItem($blockId, 'cards', $itemIndex);
```

### 3. Include the admin block list in your editor view

```blade
@include('livewire-pagebuilder::admin.block-list', ['blocks' => $blocks])
```

### 4. Render blocks on the frontend

```blade
<x-livewire-pagebuilder::renderer :blocks="$page->blocks ?? []" />
```

### 5. Rich text (CKEditor)

The `rich-text` and `two-column` admin partials expect an Alpine.js `ckeditor` component. Register it in your app's Alpine setup:

```js
import Alpine from 'alpinejs'

Alpine.data('ckeditor', (wireModelPath) => ({
    editor: null,
    init() {
        ClassicEditor.create(this.$refs.editor).then(editor => {
            this.editor = editor
            editor.model.document.on('change:data', () => {
                this.$wire.set(wireModelPath, editor.getData())
            })
        })
    },
    destroy() {
        this.editor?.destroy()
    },
}))
```

## Built-in block types

| Type | Description | Configurable data |
|------|-------------|-------------------|
| `hero` | Full-width hero section | heading, subheading, cta_text, cta_url, image_url |
| `rich-text` | CKEditor rich text area | content (HTML) |
| `two-column` | Two-column layout | left (HTML), right (HTML) |
| `card-grid` | Responsive card grid | columns (default 3), cards[] |
| `inline-image` | Image with caption | url, alt, caption, size |
| `section` | Container block | background, children[] |

`SectionBlock` is a container — it can hold `rich-text`, `card-grid`, and `inline-image` child blocks.

## Registering custom block types

Create a class extending `Block`:

```php
use NettSite\LivewirePagebuilder\Block;

class CalloutBlock extends Block
{
    public static function type(): string { return 'callout'; }
    public static function label(): string { return 'Callout'; }
    public static function defaultData(): array
    {
        return ['message' => '', 'style' => 'info'];
    }
}
```

Register it in `config/livewire-pagebuilder.php`:

```php
return [
    'blocks' => [
        // built-in blocks...
        \App\Blocks\CalloutBlock::class,
    ],
];
```

Then publish and create your admin and frontend view partials:
- `resources/views/vendor/livewire-pagebuilder/admin/blocks/callout.blade.php`
- `resources/views/vendor/livewire-pagebuilder/frontend/blocks/callout.blade.php`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [NettSite](https://github.com/nettsite)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

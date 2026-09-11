<div class="block-fields block-fields--two-column">
    <div class="block-field">
        <label>Left Column</label>
        <div x-data="ckeditor('{{ $wireModelPath }}.left')" wire:ignore>
            <div x-ref="editor"></div>
        </div>
    </div>
    <div class="block-field">
        <label>Right Column</label>
        <div x-data="ckeditor('{{ $wireModelPath }}.right')" wire:ignore>
            <div x-ref="editor"></div>
        </div>
    </div>
</div>

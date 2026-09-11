<div class="block-fields">
    <div class="block-field">
        <label>Heading</label>
        <input type="text" wire:model="{{ $wireModelPath }}.heading">
    </div>
    <div class="block-field">
        <label>Subheading</label>
        <input type="text" wire:model="{{ $wireModelPath }}.subheading">
    </div>
    <div class="block-field">
        <label>CTA Text</label>
        <input type="text" wire:model="{{ $wireModelPath }}.cta_text">
    </div>
    <div class="block-field">
        <label>CTA URL</label>
        <input type="url" wire:model="{{ $wireModelPath }}.cta_url">
    </div>
    <div class="block-field">
        <label>Image URL</label>
        <input type="url" wire:model="{{ $wireModelPath }}.image_url">
    </div>
</div>

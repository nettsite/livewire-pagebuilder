<div class="block-fields">
    <div class="block-field">
        <label>Image URL</label>
        <input type="url" wire:model="{{ $wireModelPath }}.url">
    </div>
    <div class="block-field">
        <label>Alt Text</label>
        <input type="text" wire:model="{{ $wireModelPath }}.alt">
    </div>
    <div class="block-field">
        <label>Caption</label>
        <input type="text" wire:model="{{ $wireModelPath }}.caption">
    </div>
    <div class="block-field">
        <label>Size</label>
        <select wire:model="{{ $wireModelPath }}.size">
            <option value="full">Full</option>
            <option value="wide">Wide</option>
            <option value="medium">Medium</option>
            <option value="small">Small</option>
        </select>
    </div>
</div>

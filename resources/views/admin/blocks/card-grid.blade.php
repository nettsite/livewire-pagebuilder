<div class="block-fields">
    <div class="block-field">
        <label>Columns</label>
        <select wire:model="{{ $wireModelPath }}.columns">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </div>

    <div class="block-field">
        <label>Cards</label>
        @foreach ($block['data']['cards'] ?? [] as $cardIndex => $card)
            <div class="card-grid__card" wire:key="card-{{ $block['id'] }}-{{ $cardIndex }}">
                <input type="text" placeholder="Title"
                    wire:model="{{ $wireModelPath }}.cards.{{ $cardIndex }}.title">
                <textarea placeholder="Body"
                    wire:model="{{ $wireModelPath }}.cards.{{ $cardIndex }}.body"></textarea>
                <input type="url" placeholder="Link URL"
                    wire:model="{{ $wireModelPath }}.cards.{{ $cardIndex }}.link_url">
                <button type="button"
                    wire:click="removeSubItem('{{ $block['id'] }}', 'cards', {{ $cardIndex }})">
                    Remove card
                </button>
            </div>
        @endforeach
        <button type="button"
            wire:click="addSubItem('{{ $block['id'] }}', 'cards', {{ json_encode(['title' => '', 'body' => '', 'link_url' => '']) }})">
            + Add card
        </button>
    </div>
</div>

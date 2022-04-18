<li>
    <button wire:click.prevent="bulkAction('{{ $identifier }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
            class="dropdown-item"
            title="{{ $label }}"
            type="button">
        {{ $label }}
    </button>
</li>

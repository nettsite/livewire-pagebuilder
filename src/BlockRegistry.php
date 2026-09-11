<?php

namespace NettSite\LivewirePagebuilder;

use Illuminate\Support\Str;
use InvalidArgumentException;

class BlockRegistry
{
    /** @var array<string, class-string<Block>> */
    private array $types = [];

    /** @param class-string<Block> $blockClass */
    public function register(string $blockClass): static
    {
        $this->types[$blockClass::type()] = $blockClass;

        return $this;
    }

    /** @return class-string<Block>|null */
    public function find(string $type): ?string
    {
        return $this->types[$type] ?? null;
    }

    /** @return array<string, class-string<Block>> */
    public function all(): array
    {
        return $this->types;
    }

    /** @return array{id: string, type: string, data: array<string, mixed>} */
    public function make(string $type): array
    {
        $class = $this->find($type);

        if ($class === null) {
            throw new InvalidArgumentException("Unknown block type: {$type}");
        }

        return [
            'id' => Str::uuid()->toString(),
            'type' => $type,
            'data' => $class::defaultData(),
        ];
    }
}

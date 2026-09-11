<?php

namespace NettSite\LivewirePagebuilder\Concerns;

use NettSite\LivewirePagebuilder\BlockRegistry;

trait ManagesBlocks
{
    public function addBlock(string $type): void
    {
        $this->blocks[] = app(BlockRegistry::class)->make($type);
        $this->blocks = array_values($this->blocks);
    }

    public function removeBlock(string $id): void
    {
        $index = $this->findBlockIndex($id);
        array_splice($this->blocks, $index, 1);
        $this->blocks = array_values($this->blocks);
    }

    public function moveBlockUp(string $id): void
    {
        $index = $this->findBlockIndex($id);

        if ($index === 0) {
            return;
        }

        [$this->blocks[$index - 1], $this->blocks[$index]] = [$this->blocks[$index], $this->blocks[$index - 1]];
        $this->blocks = array_values($this->blocks);
    }

    public function moveBlockDown(string $id): void
    {
        $index = $this->findBlockIndex($id);

        if ($index === count($this->blocks) - 1) {
            return;
        }

        [$this->blocks[$index + 1], $this->blocks[$index]] = [$this->blocks[$index], $this->blocks[$index + 1]];
        $this->blocks = array_values($this->blocks);
    }

    public function addChildBlock(string $parentId, string $type): void
    {
        $index = $this->findBlockIndex($parentId);
        $this->blocks[$index]['children'][] = app(BlockRegistry::class)->make($type);
        $this->blocks[$index]['children'] = array_values($this->blocks[$index]['children']);
        $this->blocks = array_values($this->blocks);
    }

    public function removeChildBlock(string $parentId, string $childId): void
    {
        $parentIndex = $this->findBlockIndex($parentId);
        $childIndex = $this->findChildIndex($parentId, $childId);
        array_splice($this->blocks[$parentIndex]['children'], $childIndex, 1);
        $this->blocks[$parentIndex]['children'] = array_values($this->blocks[$parentIndex]['children']);
        $this->blocks = array_values($this->blocks);
    }

    public function moveChildBlockUp(string $parentId, string $childId): void
    {
        $parentIndex = $this->findBlockIndex($parentId);
        $childIndex = $this->findChildIndex($parentId, $childId);

        if ($childIndex === 0) {
            return;
        }

        $children = &$this->blocks[$parentIndex]['children'];
        [$children[$childIndex - 1], $children[$childIndex]] = [$children[$childIndex], $children[$childIndex - 1]];
        $children = array_values($children);
        $this->blocks = array_values($this->blocks);
    }

    public function moveChildBlockDown(string $parentId, string $childId): void
    {
        $parentIndex = $this->findBlockIndex($parentId);
        $childIndex = $this->findChildIndex($parentId, $childId);
        $children = &$this->blocks[$parentIndex]['children'];

        if ($childIndex === count($children) - 1) {
            return;
        }

        [$children[$childIndex + 1], $children[$childIndex]] = [$children[$childIndex], $children[$childIndex + 1]];
        $children = array_values($children);
        $this->blocks = array_values($this->blocks);
    }

    public function addSubItem(string $blockId, string $dataKey, array $defaults = []): void
    {
        $index = $this->findBlockIndex($blockId);
        $this->blocks[$index]['data'][$dataKey][] = $defaults;
        $this->blocks[$index]['data'][$dataKey] = array_values($this->blocks[$index]['data'][$dataKey]);
        $this->blocks = array_values($this->blocks);
    }

    public function removeSubItem(string $blockId, string $dataKey, int $itemIndex): void
    {
        $index = $this->findBlockIndex($blockId);
        array_splice($this->blocks[$index]['data'][$dataKey], $itemIndex, 1);
        $this->blocks[$index]['data'][$dataKey] = array_values($this->blocks[$index]['data'][$dataKey]);
        $this->blocks = array_values($this->blocks);
    }

    private function findBlockIndex(string $id): int
    {
        foreach ($this->blocks as $i => $block) {
            if ($block['id'] === $id) {
                return $i;
            }
        }

        throw new \InvalidArgumentException("Block not found: {$id}");
    }

    private function findChildIndex(string $parentId, string $childId): int
    {
        $parentIndex = $this->findBlockIndex($parentId);

        foreach ($this->blocks[$parentIndex]['children'] ?? [] as $i => $child) {
            if ($child['id'] === $childId) {
                return $i;
            }
        }

        throw new \InvalidArgumentException("Child block not found: {$childId}");
    }
}

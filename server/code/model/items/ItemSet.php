<?php

namespace Model\Items;

class ItemSet
{
    protected int $itemId;

    protected int $count;

    public function __construct(
        int $itemId,
        int $count
    ) {
        $this->itemId = $itemId;
        $this->count = $count;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}

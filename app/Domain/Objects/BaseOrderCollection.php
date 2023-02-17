<?php

namespace App\Domain\Objects;

use Illuminate\Support\Collection;

class BaseOrderCollection
{
    protected function __construct(
        protected Collection $collection,
    ) {
    }

    /**
     * @param array $items
     * @return self
     */
    public static function make(array $items): self
    {
        return new self(collect($items));
    }

    /**
     * @param BaseOrderItem $orderItem
     * @return void
     */
    public function addItem(BaseOrderItem $orderItem): void
    {
        $this->collection->push($orderItem);
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function each(callable $callback): self
    {
        $this->collection->each($callback);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = '';
        $this->collection->each(function (BaseOrderItem $orderItem) use (&$result) {
            $result .= $orderItem;
        });

        return $result;
    }
}

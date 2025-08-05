<?php

namespace App\Libs;

class StringBuilder
{
    private $spaceSeparator = " ";
    private $emptyString = "";
    private $items = [];

    public function clear(): void
    {
        $this->items = [];
    }

    public function add($value): void
    {
        $this->items[] = $value;
    }

    public function toString(string $separator = null): string
    {
        $glue = $this->spaceSeparator;
        if (!is_null($separator)) {
            $glue = $separator;
        }

        if (count($this->items) > 0) {
            return implode($glue, $this->items);
        }

        return $this->emptyString;
    }
}

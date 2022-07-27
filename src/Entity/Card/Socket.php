<?php

namespace App\Entity\Card;

use JsonSerializable;

class Socket implements JsonSerializable
{
    private ?int $lvl;
    public string $type;

    public function setLvl(?int $value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        $this->lvl = $value;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}

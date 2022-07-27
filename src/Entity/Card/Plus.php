<?php

namespace App\Entity\Card;

use JsonSerializable;

class Plus implements JsonSerializable
{
    private ?int $hp;
    private ?int $atk;
    private ?int $rcv;

    public function setHp(?int $value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        $this->hp = $value;
    }

    public function setAtk(?int $value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        $this->atk = $value;
    }

    public function setRcv(?int $value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        $this->rcv = $value;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}

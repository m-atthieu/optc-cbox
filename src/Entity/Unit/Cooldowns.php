<?php

namespace App\Entity\Unit;

class Cooldowns
{
    public int $min;
    public int $max;

    public function isMax(): bool
    {
        return $this->min == $this->max;
    }
}

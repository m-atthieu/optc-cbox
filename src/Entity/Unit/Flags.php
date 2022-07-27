<?php

namespace App\Entity\Unit;

class Flags
{
    public bool $global;
    public bool $rr;
    public bool $lrr;

    public function isRr(): bool
    {
        return $this->rr;
    }

    public function isLrr(): bool
    {
        return $this->lrr;
    }
}

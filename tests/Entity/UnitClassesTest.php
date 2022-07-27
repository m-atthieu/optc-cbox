<?php

namespace App\Tests\Entity;

use App\Entity\Unit;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class UnitClassesTest extends TestCase
{
    private Unit $instance;

    public function setUp(): void
    {
        $this->instance = new Unit();
    }
}

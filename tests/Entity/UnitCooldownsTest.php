<?php

namespace App\Tests\Entity;

use App\Entity\Unit;
use App\Entity\Unit\Cooldowns;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class UnitCooldownsTest extends TestCase
{
    private Unit $instance;

    public function setUp(): void
    {
        $this->instance = new Unit();
    }

    public function testCd1IsMax(): void
    {
        $cd = new Cooldowns();
        $cd->min = 1;
        $cd->max = 1;
        $this->instance->cd = $cd;
        $this->assertEquals(1, $this->instance->getMaxCd());
    }

    public function testCd6(): void
    {
        $cd = new Cooldowns();
        $cd->min = 1;
        $cd->max = 6;
        $this->instance->cd = $cd;
        $this->assertEquals(6, $this->instance->getMaxCd());
    }
}

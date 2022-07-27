<?php

namespace App\Tests\Entity\Unit;

use App\Entity\Unit;
use App\Entity\Unit\Cooldowns;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class CooldownsTest extends TestCase
{
    private Cooldowns $instance;

    public function setUp(): void
    {
        $this->instance = new Cooldowns();
    }

    public function testCd1IsMax()
    {
        $this->instance->max = $this->instance->min = 1;
        $this->assertTrue($this->instance->isMax());
    }

    public function testCd1On2IsNotMax()
    {
        $this->instance->max = 2;
        $this->instance->min = 1;
        $this->assertFalse($this->instance->isMax());
    }
}

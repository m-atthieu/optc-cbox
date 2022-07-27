<?php

namespace App\Tests\Entity;

use App\Entity\Unit;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class UnitFlagsTest extends TestCase
{
    private Unit $instance;

    public function setUp(): void
    {
        $this->instance = new Unit();
    }

    public function testRrUnitIsNotFarmable()
    {
        $flags = new Flags();
        $flags->rr = true;
        $this->instance->flags = $flags;
        $this->assertFalse($this->instance->isFarmable());
        $this->assertTrue($this->instance->isRr());
    }

    public function testLrrUnit()
    {
        $flags = new Flags();
        $flags->lrr = true;
        $this->instance->flags = $flags;
        $this->assertTrue($this->instance->isLrr());
    }

    public function testUnitStar6IsLegend()
    {
        $flags = new Flags();
        $flags->rr = true;
        $this->instance->flags = $flags;
        $this->instance->stars = '6';
        $this->assertTrue($this->instance->isLegend());
    }

    public function testUnitStar6pIsLegend()
    {
        $flags = new Flags();
        $flags->rr = true;
        $this->instance->flags = $flags;
        $this->instance->stars = '6+';
        $this->assertTrue($this->instance->isLegend());
    }
}

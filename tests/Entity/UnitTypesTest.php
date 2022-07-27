<?php

namespace App\Tests\Entity;

use App\Entity\Unit;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class UnitTypesTest extends TestCase
{
    private Unit $instance;

    public function setUp(): void
    {
        $this->instance = new Unit();
    }

    public function testUnitWithOneTypeIsNotDual()
    {
        $this->instance->setType('INT');
        $this->assertFalse($this->instance->isDualUnit());
    }

    public function testUnitWithArrayOfOneTypeIsNotDual()
    {
        $this->instance->setType(['INT']);
        $this->assertFalse($this->instance->isDualUnit());
    }

    public function testUnitWithTwoTypesIsDual()
    {
        $this->instance->setType(['INT', 'PSY']);
        $this->assertTrue($this->instance->isDualUnit());
    }
}

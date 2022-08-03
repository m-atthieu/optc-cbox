<?php

namespace App\Tests\Entity;

use App\Entity\Unit;
use App\Entity\Unit\Details;
use App\Entity\Unit\Details\Support;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class UnitSupportTest extends TestCase
{
    private Unit $instance;

    public function setUp(): void
    {
        $this->instance = new Unit();
    }

    public function testUnitWithSupportHasSupport(): void
    {
        $support = new Support();
        $details = new Details();
        $details->support = [$support];
        $this->instance->details = $details;
        $this->assertTrue($this->instance->hasSupport());
    }
}

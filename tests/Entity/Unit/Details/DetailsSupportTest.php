<?php

namespace App\Tests\Entity\Unit\Details;

use App\Entity\Unit;
use App\Entity\Unit\Cooldowns;
use App\Entity\Unit\Details;
use App\Entity\Unit\Details\Support;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class DetailsSupportTest extends TestCase
{
    private Details $instance;

    public function setUp(): void
    {
        $this->instance = new Details();
    }

    public function testDetailsCanBeAssignedASingularSupport()
    {
        $this->markTestSkipped('not yet');
        $support = new Support();
        $this->instance->support = $support;
        $this->assertTrue($this->instance->hasSupport());
    }

    public function testDetailsCanBeAssignedAnArrayOfSupport()
    {
        $support = new Support();
        $this->instance->support = [$support];
        $this->assertTrue($this->instance->hasSupport());
    }
}

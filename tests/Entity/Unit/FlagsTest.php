<?php

namespace App\Tests\Entity\Unit;

use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class FlagsTest extends TestCase
{
    private Flags $instance;

    public function setUp(): void
    {
        $this->instance = new Flags();
    }

    public function testRRFlag()
    {
        $this->instance->rr = true;
        $this->assertTrue($this->instance->isRr());
    }

    public function testLrrFlag()
    {
        $this->instance->rr = true;
        $this->instance->lrr = true;
        $this->assertTrue($this->instance->isLrr());
    }
}

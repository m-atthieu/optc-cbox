<?php

namespace App\Tests\Model;

use App\Model\Unit;
use App\Model\UnitFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UnitFactoryTest extends KernelTestCase
{
    private UnitFactory $instance;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->instance = new UnitFactory($kernel);
    }

    public function testInstanceHasUnits()
    {
        $this->instance->load([
            1 => [

            ]
        ]);
        $units = $this->instance->all();
        $this->assertGreaterThan(0, count($units));
    }

    public function testUnfakeId1is1()
    {
        // that is to say, jp id:1 is the same as glb id:1
        $actual = $this->instance->unfakeId(1);
        $this->assertEquals(1, $actual);
    }

    public function testUnfakeLocalSeaMonster()
    {
        // LSM JP id : 3383, GLB id: 5065
        $actual = $this->instance->unfakeId(3383);
        $this->assertEquals(5065, $actual);
    }

    public function testUnitReturnsUnit()
    {
        $this->markTestSkipped('it should be an integration test');
        $actual = $this->instance->unit(5065);
        $this->assertTrue(is_a($actual, Unit::class));
    }

    public function tearDown(): void
    {
        unset($this->instance);
    }
}

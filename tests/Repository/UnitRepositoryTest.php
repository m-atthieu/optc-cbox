<?php

namespace App\Tests\Repository;

use App\Model\Unit;
use App\Model\UnitFactory;
use App\Repository\UnitRepository;
use JsonMapper\JsonMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UnitRepositoryTest extends TestCase
{
    private UnitRepository $instance;

    public function setUp(): void
    {
        $mapper = $this->getMockBuilder(JsonMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->instance = new UnitRepository($mapper);
    }

    public function testInstanceHasUnits(): void
    {
        $this->instance->loadFromJsonFile(__DIR__ . '/../fixtures/units-only-1-unit.json');
        $units = $this->instance->findAll();
        $this->assertGreaterThan(0, count($units));
    }

    public function testUnfakeId1is1(): void
    {
        // that is to say, jp id:1 is the same as glb id:1
        $actual = $this->instance->unfakeId(1);
        $this->assertEquals(1, $actual);
    }

    public function testUnfakeLocalSeaMonster(): void
    {
        // LSM JP id : 3383, GLB id: 5065
        $actual = $this->instance->unfakeId(3383);
        $this->assertEquals(5065, $actual);
    }

    public function testUnitReturnsUnit(): void
    {
        $actual = null;
        $this->markTestSkipped('it should be an integration test');
        $actual = $this->instance->unit(5065);
        $this->assertTrue(is_a($actual, Unit::class));
    }

    public function tearDown(): void
    {
        unset($this->instance);
    }
}

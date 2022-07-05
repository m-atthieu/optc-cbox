<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\UserBox;
use App\Factory\UserBoxFactory;
use App\Model\CardSorter;
use App\Repository\UserBoxRepository;
use JsonMapper\JsonMapper;
use PHPUnit\Framework\TestCase;

class UserBoxRepositoryTest extends TestCase
{
    private UserBoxRepository $instance;

    public function setUp(): void
    {
        $jsonMapper = $this->getMockBuilder(JsonMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cardSorter = $this->getMockBuilder(CardSorter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->instance = new UserBoxRepository($jsonMapper, __DIR__ . '/../fixtures', $cardSorter);
    }

    public function testCanLoadOneUserBox()
    {
        $box = $this->instance->findOneByUserId('391245463');
        $this->assertFalse($box == null, 'UserBox should not be null');
        $this->assertTrue(is_a($box, UserBox::class), 'findOneById should return a UserBox');
    }
}

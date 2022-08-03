<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Card;
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
        $this->assertNotEquals(0, count($box->cards));
        $this->assertTrue(is_a($box, UserBox::class), 'findOneById should return a UserBox');
    }

    public function testUserBoxIsSaved()
    {
        $id = '391245463';
        $cardSorter = $this->getMockBuilder(CardSorter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $box = new UserBox($cardSorter);
        $card = new Card();
        $box->addCard($card);
        $this->assertArrayHasKey(0, $box->cards);
        $expected = strlen(json_encode($box->cards, JSON_PRETTY_PRINT));
        $jsonMapper = $this->getMockBuilder(JsonMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo =  new UserBoxRepository($jsonMapper, '/tmp', $cardSorter);
        $actual = $repo->save($box, $id);
        $this->assertEquals($expected, $actual, "expected to write {$expected}, wrote {$actual}");
    }
}

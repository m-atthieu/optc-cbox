<?php

declare(strict_types=1);

namespace App\Tests\Command\OptcDb;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class TransformAndMergeCommandTest extends KernelTestCase
{
    private ?CommandTester $tester;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('optc-db:transform-and-merge');
        $this->tester = new CommandTester($command);
    }

    public function tearDown(): void
    {
        $this->tester = null;
    }

    public function testExecute(): void
    {
        $this->markTestSkipped();
        $this->tester->execute([]);

        $this->tester->assertCommandIsSuccessful();
    }
}

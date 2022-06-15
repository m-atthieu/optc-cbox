<?php

declare(strict_types=1);

/*
 * example found at http://www.inanzzz.com/index.php/post/c7jb/testing-symfony-console-command-with-phpunit
 */

namespace App\Tests\Command\OptcDb;

use App\Command\OptcDb\RetreiveCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RetreiveCommandTest extends KernelTestCase
{
    private ?CommandTester $tester;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $fs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient = $this->getMockBuilder(HttpClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $application->add(new RetreiveCommand($kernel, $fs, $httpClient));
        $command = $application->find('optc-db:retreive');
        $this->tester = new CommandTester($command);
    }

    public function tearDown(): void
    {
        $this->tester = null;
    }

    public function testExecute(): void
    {
        $this->tester->execute([]);
        $this->tester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $this->tester->getDisplay();
    }
}

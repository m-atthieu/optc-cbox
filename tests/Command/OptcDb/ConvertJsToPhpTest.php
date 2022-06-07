<?php

namespace App\Tests\Command\OptcDb;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConvertJsToPhpTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('optc-db:convert-js-to-php');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
    }
}

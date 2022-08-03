<?php

declare(strict_types=1);

namespace App\Tests\Command\OptcDb;

use App\Command\OptcDb\ConvertJsToPhp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConvertJsToPhpTest extends KernelTestCase
{
    private ?CommandTester $tester;
    private ?ConvertJsToPhp $command;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $this->command = new ConvertJsToPhp($kernel);
        $application->add($this->command);
        $command = $application->find('optc-db:convert-js-to-php');
        $this->tester = new CommandTester($command);
    }

    public function tearDown(): void
    {
        $this->command = null;
        $this->tester = null;
    }

    public function testExecute(): void
    {
        $this->markTestSkipped();
        $this->tester->execute([]);
        $this->tester->assertCommandIsSuccessful();
    }

    public function testTransformDetailsBasicTransformation(): void
    {
        $content = "window.details = {
            1: {
            }
        };";
        $result = $this->command->transformDetails($content);
        $this->assertStringContainsString('return', $result);
    }

    public function testTransformUnitsCommonBasicTransformation(): void
    {
        $content = "window.units = [];";
        $result = $this->command->transformUnitsCommon($content);
        $this->assertStringContainsString('return', $result);
    }

    public function testTransformUnitsGlbBasicTransformation(): void
    {
        $content = "var globalExUnits = [];";
        $result = $this->command->transformUnitsGlb($content);
        $this->assertStringContainsString('return', $result);
    }
}

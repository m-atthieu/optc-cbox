<?php

namespace App\Tests\Entity;

use App\Entity\Unit;
use App\Entity\Unit\Cooldowns;
use App\Entity\Unit\Details;
use App\Entity\Unit\Flags;
use PHPUnit\Framework\TestCase;

class DetailsLimitBreakPlusTest extends TestCase
{
    private Details $instance;

    public function setUp(): void
    {
        $this->instance = new Details();
    }
}

<?php

namespace App\Entity\Unit;

use App\Entity\Unit\Details\Limit;
use App\Entity\Unit\Details\Potential;
use App\Entity\Unit\Details\Support;

class Details
{
    /** @var Limit[] */
    public array $limit;
    /** @var Potential[]  */
    public array $potential;
    /** @var Support[] */
    public array $support;

    public function hasSupport(): bool
    {
        return count($this->support) > 0;
    }
}

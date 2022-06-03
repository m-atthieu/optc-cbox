<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * Gets the path to the data directory.
     */
    public function getStorageDir(): string
    {
        return $this->getProjectDir() . '/var/storage/' . $this->environment;
    }
}

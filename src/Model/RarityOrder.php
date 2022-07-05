<?php

namespace App\Model;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class RarityOrder
{
    private $data;

    private $order = [
        '6+' => 0,
        6    => 1,
        '5+' => 2,
        5 => 3,
        '4+' => 4,
        4 => 5,
        3 => 6,
        2 => 7,
        1 => 8
    ];

    public function getForCard(Card $card)
    {
        return $this->order[$card->getRarity()];
    }
}

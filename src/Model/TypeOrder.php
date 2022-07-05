<?php

namespace App\Model;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class TypeOrder
{
    private $orders = [
        'STR' => 1,
        'DEX' => 2,
        'QCK' => 3,
        'PSY' => 4,
        'INT' => 5
    ];

    public function getForCard(Card $card)
    {
        if ($card->isDualCard()) {
            return 0;
        }
        return $this->orders[$card->getType()];
    }
}

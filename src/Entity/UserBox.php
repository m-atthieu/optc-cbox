<?php

namespace App\Entity;

use App\Model\CardSorter;
use App\Model\FamilyOrder;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class UserBox
{
    public $cards;
    public $plus = [
           'hp' => 0,
           'atk' => 0,
           'rcv' => 0
    ];
    public $max_chr = 0;

    public function __construct(private CardSorter $cardSorter)
    {
        $this->cards = [];
    }

    private function countPlus(Card $card)
    {
        $this->plus['hp'] += $card->plus['hp'];
        $this->plus['atk'] += $card->plus['atk'];
        $this->plus['rcv'] += $card->plus['rcv'];
    }

    public function &getCard($card_id)
    {
        for ($i = 0; $i < count($this->cards); ++$i) {
            if ($this->cards[$i]->card_id == $card_id) {
                return $this->cards[$i];
            }
        }
        return false;
    }

    public function addCard(Card $card)
    {
        if ($card->chr == 0) {
            $card->chr = max(count($this->cards), $this->max_chr) + 1;
        }

        $this->cards[] = $card;
    }

    public function remove($card_id)
    {
        $this->cards = array_filter($this->cards, function ($card) use ($card_id) {
            return $card->card_id != $card_id;
        });
    }

    /*public function save()
    {
        $filename = APP_DIR . "/var/data/user_box/391245463.json";
        if((isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'optc.localhost') || ! isset($_SERVER['SERVER_NAME'])){
        file_put_contents($filename, json_encode($this->cards, JSON_PRETTY_PRINT));
        }
    }*/



    public function hasCardWithUnit($unit_id)
    {
        foreach ($this->cards as $card) {
            if ($card->unit_id == $unit_id) {
                return true;
            }
        }
        return false;
    }

    public function cardsWithUnitId($unit_id)
    {
        $cards = [];
        foreach ($this->cards as $card) {
            if ($card->unit_id == $unit_id) {
                $cards[] = $card;
            }
        }
        return $cards;
    }
}

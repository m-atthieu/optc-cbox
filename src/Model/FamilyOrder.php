<?php

namespace App\Model;

use App\Entity\Card;

class FamilyOrder
{
    private $data;

    public function __construct(string $filename)
    {
        $data = json_decode(file_get_contents($filename), true);

        $this->data = $this->initData($data);
    }

    private function initData($data)
    {
        $d = [];
        foreach ($data as $n) {
            if (is_string($n)) {
                $d[] = $n;
            }
            if (is_array($n)) {
                foreach ($n as $_n) {
                    $d[] = $_n;
                }
            }
        }

        $order = array_unique($d);

        // TODO it seems there's some re-ordering to do.
        // Arlong, Ace or mihawk, Lucci should move
        // sanji/judge too

        return array_flip($order);
    }

    private function internalGet($family_name)
    {
        if (! is_null($family_name) && array_key_exists($family_name, $this->data)) {
            return $this->data[$family_name];
        } else {
            //echo "9999\n";
            return 9999;
        }
    }

    public function get($family_name)
    {
        //var_dump($family_name);
        if (is_array($family_name)) {
            $f = [];
            foreach ($family_name as $name) {
                $f[] = $this->internalGet($name);
            }
            return min($f);
        } else {
            return $this->internalGet($family_name);
        }
    }

    public function getForCard(Card $card)
    {
        return $this->get($card->getFamily());
    }
}

<?php

namespace App\Repository;

use App\Entity\Card;
use App\Entity\UserBox;
use App\Factory\UserBoxFactory;
use App\Model\CardSorter;
use App\Model\FamilyOrder;
use JsonMapper\JsonMapper;

class UserBoxRepository
{
    public function __construct(
        private JsonMapper $mapper,
        private string $base_path,
        private CardSorter $cardSorter
    ) {
    }

    private function loadFromFile(string $path): ?UserBox
    {
        $objects = json_decode(file_get_contents($path));
        $userbox = new UserBox($this->cardSorter);
        foreach ($objects as $object) {
            $card = new Card();
            $this->mapper->mapObject($object, $card);
            $userbox->addCard($card);
        }
        return $userbox;
    }

    public function findOneByUserId(string $box_id): ?UserBox
    {
        return $this->loadFromFile($this->base_path . "/{$box_id}.json");
    }

    public function save(UserBox $user_box, string $box_id): int|false
    {
        return file_put_contents("{$this->base_path}/{$box_id}.json", json_encode($user_box->cards, JSON_PRETTY_PRINT));
    }
}

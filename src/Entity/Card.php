<?php

namespace App\Entity;

use App\Entity\Card\Plus;
use App\Entity\Card\Socket;
use Ramsey\Uuid\Uuid;
use JsonSerializable;

class Card implements JsonSerializable
{
    //private $data;
    //private $unit;
    public string $card_id;
    public int $lvl;
    public int $lb;
    public int $unit_id;
    private ?int $support;
    public int $llb;
    public Plus $plus;
    public int $cd;
    public string $note;
    public string $hint;
    public int $get_date;
    public string $date;
    public array $potential;
    /** @var Socket[] */
    public array $sockets;

    public function __construct()
    {
    }

    public function setSupport(?int $value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        $this->support = $value;
    }

    public function jsonSerialize(): mixed
    {
        return (object) get_object_vars($this);
        //return $this->data;
    }

    public function initPfStats()
    {
        $this->data['pfs'] = 1;
        $this->data['pfa'] = 1;
    }

    public function addLlb($default_value = 0)
    {
        if (! array_key_exists('llb', $this->data)) {
            $this->data['llb'] = $default_value;
        }
    }

    public static function create($unit_id)
    {
        $default_data = [
            "card_id" => Uuid::uuid4()->toString(),
            "cd" => 1,
            "date" => '',
            "get_date" => 0, // à remplacer par CHR
            "hint" => '',
            "lb" => 0,
            "lvl" => 5,
            "note" => "",
            "plus" => [
                "atk" => 0,
                "hp" => 0,
                "rcv" => 0
            ],
            "potential" => [],
            "sockets" => [],
            "unit_id" => $unit_id,
            'support' => 0,
            'pfa' => 1,
            'pfs' => 1
        ];
        $card = new Card();
        $card->initFromJSON($default_data);
        return $card;
    }

    public function update($data)
    {
        $keys = [ 'hint', 'lvl', 'cd', 'lb', 'support', 'date', 'get_date', 'note' ];
        foreach ($keys as $key) {
            $this->data[$key] = $data[$key];
        }
        $this->data['plus']['hp']  = $data['plus_hp'];
        $this->data['plus']['atk'] = $data['plus_atk'];
        $this->data['plus']['rcv'] = $data['plus_rcv'];
        // socket
        // potential
        // check card_id & unit_id
    }

    /*function initFromJSON(array $data)
    {
        $this->data = $data;
        $this->unit = UnitFactory::getInstance()->unit($data['unit_id']);
    }*/

    /*function disabled__get($key)
    {
        switch ($key) {
            case 'unit':
                return $this->unit;
            case 'support':
                return $this->getsupport();
            case 'chr':
                return $this->data['get_date'];
            case 'pfs':
                return array_key_exists('pfs', $this->data) ? $this->data['pfs'] : 1;
            default:
                return $this->data[$key];
        }
    }

    function disabled__set($key, $value)
    {
        switch ($key) {
            case 'plus_hp':
                $this->data['plus']['hp'] = $value;
                break;
            case 'plus_atk':
                $this->data['plus']['atk'] = $value;
                break;
            case 'plus_rcv':
                $this->data['plus']['rcv'] = $value;
                break;
            case 'chr':
                $this->data['get_date'] = $value;
                break;
            case 'date':
            case 'get_date':
            case 'lb':
                $this->data[$key] = $value;
                break;
        }
    }

    function __isset($key)
    {
        switch ($key) {
            case 'unit':
            case 'support':
            case 'pfs':
                return true;
            default:
                return array_key_exists($key, $this->data);
        }
    }*/

    public function plusCount()
    {
        return $this->data['plus']['hp'] +
            $this->data['plus']['atk'] +
            $this->data['plus']['rcv'];
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getType()
    {
        return $this->unit->type;
    }

    public function getRarity()
    {
        return $this->unit->stars;
    }

    public function getFamily()
    {
        return $this->unit->family;
    }

    public function getName()
    {
        return $this->unit->name;
    }

    public function isDualCard()
    {
        return $this->unit->isDualUnit();
    }

    public function getSupport()
    {
        return array_key_exists('support', $this->data) ? $this->data['support'] : 0;
    }

    public function isMaxLevel()
    {
        return $this->data['lvl'] == $this->unit->max_lvl;
    }

    public function setMaxLvl()
    {
        $this->data['lvl'] = $this->unit->max_lvl;
    }

    public function isMaxCd()
    {
        return $this->data['cd'] == $this->unit->max_cd;
    }

    public function setMaxCd()
    {
        $this->data['cd'] = $this->unit->max_cd;
    }

    public function isMaxLb()
    {
        if ($this->unit->max_lb == 0) {
            return false;
        }
        return $this->data['lb'] >= $this->unit->max_lb;
    }

    public function isLbKeyUnlocked()
    {
        $key_level = $this->unit->lb_key_level();
        return ($this->data['lb'] >= $key_level);
    }

    public function lbUnlockKey()
    {
        $this->data['lb'] = $this->unit->lb_key_level();
    }

    public function isMaxLbp()
    {
        if (! $this->isMaxLb() || ! $this->unit->hasLbPlus()) {
            return false;
        }

        return ($this->data['lb'] == $this->unit->max_lb_p);
    }

    public function isRainbow()
    {
        if (! $this->unit->hasLb()) {
            return false;
        }

        $max_lb = $this->isMaxLb();

        $nb_pot = $this->unit->nb_potential();
        $max_pot = true;
        if (count($this->data['potential']) < $nb_pot) {
            return false;
        }
        for ($i = 0; $i < $nb_pot; ++$i) {
            if ($this->data['potential'][$i] != 5) {
                $max_pot = false;
                break;
            }
        }

        return $max_lb && $max_pot;
    }

    public function isDoubleRainbow()
    {
        if (! $this->unit->hasLb() || ! $this->isRainbow() || ! $this->unit->hasLbPlus()) {
            return false;
        }

        // TODO to finish
        if (! $this->isMaxLbp()) {
             return false;
        }

        $nb_pot = $this->unit->nb_potential_lbp();
        $count = count($this->data['potential']);
        if ($count != $nb_pot) {
            return false;
        }
        $max_pot = true;
        for ($i = 0; $i < $nb_pot; ++$i) {
            if ($this->data['potential'][$i] != 5) {
                $max_pot = false;
                break;
            }
        }
        return $max_pot;
    }

    public function setMaxLb()
    {
        $this->data['lb'] = $this->unit->max_lb;
    }

    public function setLb($index, $level)
    {
        if (count($this->data['potential']) < $index - 1) {
            for ($i = 0; $i <= $index; ++$i) {
                   $this->data['potential'][] = 1;
            }
        }
        $this->data['potential'][$index] = $level;
    }

    public function setMaxLbp()
    {
        $this->data['lb'] = $this->unit->max_lb_p;
    }

    public function unsetRainbow()
    {
        $this->data['lb'] = 0;
        $this->data['potential'] = [];
    }

    public function setRainbow()
    {
        $this->setMaxLb();
        if (! is_array($this->data['potential'])) {
             $this->data['potential'] = [];
        }

        for ($i = 0; $i < $this->unit->nb_potential(); ++$i) {
               $this->data['potential'][$i] = 5;
        }
    }

    public function setDoubleRainbow()
    {
        $this->setMaxLbp();
        for ($i = 0; $i < $this->unit->nb_potential_lbp(); ++$i) {
               $this->data['potential'][$i] = 5;
        }
    }

    public function isMaxSupport()
    {
        if (! $this->unit->has_support() || ! array_key_exists('support', $this->data)) {
            return false;
        }

        return $this->data['support'] == 5;
    }

    public function socketType($index)
    {
        if ($index >= count($this->data['sockets'])) {
            return 'blank-socket';
        }
        return $this->data['sockets'][$index]['type'];
    }

    public function socketLevel($index)
    {
        if ($index >= count($this->data['sockets']) || is_null($this->data['sockets'][$index]['lvl'])) {
            return '-';
        }
        return $this->data['sockets'][$index]['lvl'];
    }

    public function potentialType($index)
    {
        if ($index >= count($this->data['potential'])) {
            return 'blank-socket';
        }
        return $this->unit->details['potential'][$index]['Name'];
        return $this->data['potential'][$index];
    }

    public function potentialLevel($index)
    {
        if ($index >= count($this->data['potential']) || is_null($this->data['potential'][$index])) {
            return '-';
        }
        return $this->data['potential'][$index];
    }

    public function setMaxSupport()
    {
        $this->data['support'] = 5;
    }

    public function nbSocket()
    {
        $base_sockets = $this->unit->sockets;
        $lb_sockets = $this->unit->nb_additional_socket($this->data['lb']);
        return $base_sockets + $lb_sockets;
    }

    public function hasPotential()
    {
        return $this->nbPotential() >= 1;
    }

    public function nbPotential()
    {
        // must return nb potential unlocked for this LB
        return count($this->data['potential']);
    }

    public function evolve()
    {
        if ($this->unit->has_evolution()) {
            $evolution_id = $this->unit->get_evolution_id();
            $this->data['unit_id'] = $evolution_id;
        }
    }

    public function isMaxPfs()
    {
        return array_key_exists('pfs', $this->data) && $this->data['pfs'] == 10;
    }

    public function isMaxPfa()
    {
        return array_key_exists('pfa', $this->data) && $this->pfa == 5;
    }
}

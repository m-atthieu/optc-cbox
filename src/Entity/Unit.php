<?php

namespace App\Entity;

use App\Entity\Unit\Cooldowns;
use App\Entity\Unit\Details;
use App\Entity\Unit\Evolutions;
use App\Entity\Unit\Flags;
use App\Model\PotentialType;
use JsonSerializable;

class Unit implements JsonSerializable
{
    //private $data;
    public int $id;
    public string $name;
    public string $stars;
    public int $sockets;
    private array $type;
    private array $class;
    public Details $details;
    public Evolutions $evolutions;
    public Flags $flags;
    public Cooldowns $cd;

    public function __construct()
    {
        $this->type = [];
    }

    public function setType(mixed $value)
    {
        if (is_array($value)) {
            $this->type = $value;
        } else {
            $this->type[] = $value;
        }
    }

    public function setClass(mixed $value)
    {
        if (is_array($value)) {
            $this->class = $value;
        } else {
            $this->class[] = $value;
        }
    }

    public function jsonSerialize(): mixed
    {
        return (object) get_object_vars($this);
        //return $this->data;
    }

    public function __get($key)
    {
        switch ($key) {
            case 'max_cd':
                return $this->getMaxCd();
            case 'max_lb':
                return $this->getMaxLb();
            case 'max_lb_p':
                return $this->getMaxLbPlus();
            case 'classes':
                return $this->getClasses();
            case 'family':
                if (! array_key_exists($key, $this->data)) {
                    return '';
                } else {
                    return $this->data[$key];
                }
            case 'flags':
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return [];
                }
            default:
                return $this->data[$key];
        }
    }

    public function __isset($key)
    {
        switch ($key) {
            case 'max_cd':
            case 'max_lb':
            case 'classes':
            case 'flags':
                return true;
            default:
                return array_key_exists($key, $this->data);
        }
    }

    /*********/
    /* FLAGS */
    /*********/
    public function isFarmable()
    {
        return ! $this->isRr();
    }

    public function isRr()
    {
        return $this->flags->isRr();
    }

    public function isLrr()
    {
        return $this->flags->isLrr();
    }

    public function isLegend()
    {
        return $this->isRr() && $this->stars >= 6;
    }

    /*********/
    /* TYPES */
    /*********/
    public function isDualUnit()
    {
        return count($this->type) > 1;
    }

    /***********/
    /* CLASSES */
    /***********/
    public function getClasses()
    {
        if (is_array($this->data['class'][0])) {
            return join(',', array_map(function ($e) {
                return join(',', $e);
            }, $this->data['class']));
        }
        return join(',', $this->data['class']);
    }

    /*************/
    /* COOLDOWNS */
    /*************/
    public function getMaxCd()
    {
        /*if (! array_key_exists('cooldowns', $this->data)) {
            return 1;
        } else {
            return (intval($this->data['cooldowns']['max']) - intval($this->data['cooldowns']['min']) + 1);
        }*/
        return $this->cd->max - $this->cd->min + 1;
    }

    /***************/
    /* LIMIT BREAK */
    /***************/
    public function getMaxLb()
    {
        // beware of double rainbow
        if (! array_key_exists('limit', $this->data['details'])) {
            return 0;
        } else {
            $nb_limits = 0;//count($this->data['details']['limit']);
            foreach ($this->data['details']['limit'] as $limit) {
                if ($limit['description'] == 'LOCKED WITH KEY') {
                    break;
                }
                ++$nb_limits;
            }
          //if($nb_limits > 30){ return 30; }
            return $nb_limits;
        }
    }

    public function getLbKeyLevel()
    {
        $i = 1;
        foreach ($this->data['details']['limit'] as $limit) {
            if ($limit['description'] == 'LOCKED WITH KEY') {
                break;
            }
            ++$i;
        }
        return $i;
    }

    public function getMaxLbPlus()
    {
        if (! $this->hasLbPlus()) {
            return $this->getMaxLb();
        }

        return count($this->data['details']['limit']);
    }

    public function hasLb()
    {
        return array_key_exists('details', $this->data) && array_key_exists('limit', $this->data['details']);
    }

    public function hasLbPlus()
    {
        if (! $this->hasLb()) {
            return false;
        }

        foreach ($this->data['details']['limit'] as $limit) {
            if (preg_match('/LOCKED WITH KEY/', $limit['description'])) {
                return true;
            }
        }
        return false;
    }

    public function has3rdPotential()
    {
        if (! $this->hasLb()) {
            return false;
        }
        if (! $this->hasLbPlus()) {
            return false;
        }
        $last = count($this->data['details']['limit']) - 1;
        //return preg_match("/Acquire Potential /", $this->data['details']['limit'][$last]['description']);
        return count($this->data['details']['potential']) == 3;
    }

    public function hasSupport()
    {
        //return array_key_exists('support', $this->data['details']);
        return $this->details->hasSupport();
    }

    public function hasPotential()
    {
        return $this->getNumPotential() > 0;
    }

    public function getNumPotential()
    {
        if (! array_key_exists('limit', $this->data['details'])) {
            return 0;
        }

        if (! array_key_exists('potential', $this->data['details'])) {
            return 0;
        }
        $p = 0;
        foreach ($this->data['details']['limit'] as $limit) {
            if (preg_match('/LOCKED WITH KEY/', $limit['description'])) {
                break;
            }
            if (preg_match('/Acquire Potential/', $limit['description'])) {
                ++$p;
            }
        }
        return $p; // count($this->data['details']['potential']);
    }

    public function getPotentialNameAt($index)
    {
        $name = $this->data['details']['potential'][$index]['Name'];
        if (isset(PotentialType::$errata[$name])) {
            $name = PotentialType::$errata[$name];
        }
        return $name;
    }

    public function getPotentialIconAt($index)
    {
        $name = $this->getPotentialNameAt($index);
        return PotentialType::$p_icons[$name];
    }

    public function getNumPotentialLbp()
    {
        return count($this->data['details']['potential']);
    }

    public function hasEvolution()
    {
        return array_key_exists('evolutions', $this->data);
    }

    public function getEvolutionId()
    {
        if (is_array($this->data['evolutions']['evolution'])) {
            return $this->data['evolutions']['evolution'][0];
        } else {
            return $this->data['evolutions']['evolution'];
        }
    }

    public function hasSocket()
    {
        return $this->data['sockets'] > 0;
    }

    public function getNumAdditionalSocketAtLb($lb)
    {
        if (! array_key_exists('limit', $this->data['details'])) {
            return 0;
        }
        $i = 0;
        $additional = array_filter($this->data['details']['limit'], function ($limit) use ($i, $lb) {
            ++$i;
            return ($i - 1) < $lb && preg_match('/^Acquire 1 additional Socket slot/', $limit['description']);
        });
        return count($additional);
    }

    public function hasPf()
    {
        return array_key_exists('festAbility', $this->data['details']) || array_key_exists('rumble', $this->data);
    }
}

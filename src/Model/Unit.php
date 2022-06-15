<?php

namespace App\Model;

use JsonSerializable;

class Unit implements JsonSerializable
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        //return (object) get_object_vars($this);
        return $this->data;
    }

    function __get($key)
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

    function __isset($key)
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

    function is_farmable()
    {
        return ! $this->is_rr();
    }

    function is_rr()
    {
        return array_key_exists('flags', $this->data) &&
            array_key_exists('rr', $this->data['flags']) &&
            $this->data['flags']['rr'] == 1;
    }

    function is_lrr()
    {
        return array_key_exists('flags', $this->data) &&
            array_key_exists('rr', $this->data['flags']) &&
            array_key_exists('lrr', $this->data['flags']) &&
            $this->data['flags']['lrr'] == 1;
    }

    function is_legend()
    {
        return $this->is_rr() && $this->data['stars'] >= 6;
    }

    function isDualUnit()
    {
        return is_array($this->type);
    }

    function getClasses()
    {
        if (is_array($this->data['class'][0])) {
            return join(',', array_map(function ($e) {
                return join(',', $e);
            }, $this->data['class']));
        }
        return join(',', $this->data['class']);
    }

    function getMaxCd()
    {
        if (! array_key_exists('cooldowns', $this->data)) {
            return 1;
        } else {
            return (intval($this->data['cooldowns']['max']) - intval($this->data['cooldowns']['min']) + 1);
        }
    }

    function getMaxLb()
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

    function lb_key_level()
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

    function getMaxLbPlus()
    {
        if (! $this->hasLbPlus()) {
            return $this->getMaxLb();
        }

        return count($this->data['details']['limit']);
    }

    function has_lb()
    {
        return $this->hasLb();
    }

    function hasLb()
    {
        return array_key_exists('details', $this->data) && array_key_exists('limit', $this->data['details']);
    }

    function hasLbPlus()
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

    function has3rdPotential()
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

    function has_support()
    {
        return array_key_exists('support', $this->data['details']);
    }

    function has_potential()
    {
        return $this->nb_potential() > 0;
    }

    function nb_potential()
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

    function potential_name($index)
    {
        $name = $this->data['details']['potential'][$index]['Name'];
        if (isset(PotentialType::$errata[$name])) {
            $name = PotentialType::$errata[$name];
        }
        return $name;
    }

    function potential_icon($index)
    {
        $name = $this->potential_name($index);
        return PotentialType::$p_icons[$name];
    }

    function nb_potential_lbp()
    {
        return count($this->data['details']['potential']);
    }

    function has_evolution()
    {
        return array_key_exists('evolutions', $this->data);
    }

    function get_evolution_id()
    {
        if (is_array($this->data['evolutions']['evolution'])) {
            return $this->data['evolutions']['evolution'][0];
        } else {
            return $this->data['evolutions']['evolution'];
        }
    }

    function has_socket()
    {
        return $this->data['sockets'] > 0;
    }

    function nb_additional_socket($lb)
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

    function has_pf()
    {
        return array_key_exists('festAbility', $this->data['details']) || array_key_exists('rumble', $this->data);
    }
}

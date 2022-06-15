<?php

namespace App\Model;

class PotentialType
{
	static $errata = [
	       'Slot Bind Self-reduction' => 'Reduce Slot Bind duration',
	       'Enrage' => 'Enrage/Reduce Increase Damage Taken duration'
	];

	static $p_icons = array(
			//'Slot Bind Self-reduction' => 'p_slot_bind.png',
			'Reduce Slot Bind duration' => 'p_slot_bind.png',
			'Reduce No Healing duration' => 'p_red_no_healing.png',
			'Barrier Penetration' => 'p_barrier_penetration.png',
			'Pinch Healing' => 'p_pinch_healing.png',
			//'Enrage' => 'p_enrage.png',
			'Enrage/Reduce Increase Damage Taken duration' => 'p_enrage.png',
			'Critical Hit' => 'p_critical.png',
			'Cooldown Reduction' => 'p_cooldown.png',
			'Double Special Activation' => 'p_double_special.png',
			'[STR] Damage Reduction' => 'p_red_str.png',
			'[DEX] Damage Reduction' => 'p_red_dex.png',
			'[QCK] Damage Reduction' => 'p_red_qck.png',
			'[PSY] Damage Reduction' => 'p_red_psy.png',
			'[INT] Damage Reduction' => 'p_red_int.png',
			'Reduce Ship Bind duration' => 'p_ship_bind.png',
			'Nutrition/Reduce Hunger duration' => 'p_hunger.png',
			'Nutrition/Reduce Hunger stacks' => 'p_hunger.png',
			'Last Tap' => 'p_last_tap.png',
			'Reduce Slot Barrier duration' => 'p_slot_bind.png',
			'Reduce Sailor Despair duration' => 'p_ship_bind.png',
			'Reduce Healing Reduction duration' => 'p_red_no_healing.png'
	);
	
	static function icon_for_name($name)
	{
		if($name == '' || ! array_key_exists($name, self::$p_icons)){
			return 'blank-socket.png';
		}
		return self::$p_icons[$name];
	}
}

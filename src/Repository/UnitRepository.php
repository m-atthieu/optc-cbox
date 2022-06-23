<?php

namespace App\Repository;

use App\Kernel;

class UnitRepository
{
    private $units;

    public function __construct(Kernel $kernel, array $data = [])
    {
        $storage_dir = $kernel->getStorageDir();
        //$data = json_decode(file_get_contents("{$storage_dir}/units.json"), true);
        $this->units = $data;
    }

    public function loadFromJsonFile(string $path): void
    {
        $content = file_get_contents($path);
        $data = json_decode($content, true);
        $this->load($data);
    }

    public function load(array $data): void
    {
        $this->units = $data;
    }

    public function unit(int $unit_id): ?Unit
    {
        foreach ($this->units as $unit) {
            // TODO we should definitively log that
            if (! isset($unit['id'])) {
                continue;
            }
            if ($unit['id'] == $unit_id) {
                return new Unit($unit);
            }
        }

        throw new \Exception("Unit not found for unit_id '{$unit_id}'");
    }

    public function all()
    {
        return $this->units;
    }

    public const GLB_EXCLUSIVE_LIMIT = 4985;

    public static $unfake = [
        // optc-db-id, global-ingame-id, name
        // KungFu Luffy
        [ 4986, 5013, 'Monkey D. Luffy, Kung Fu Training'],
        [ 4987, 5014, 'Monkey D. Luffy, To Become a True Kung Fu Master'],
        // Croc & Daz
        [ 2399, 5015, 'Crocodile & Daz, Revived Duo'],
        // Lucci v2 6+
        [ 2784, 5016, 'Lucci, Beastman - Absolute Prevention of Evil'],
        // Raid Garp & Sengoku
        [ 2551, 5017, 'Admiral Sengoku and Vice Admiral Garp'], // jp: 2551
        [ 2552, 5018, 'Admiral Sengoku and Vice Admiral Garp, Dawn of the Great Era of Piracy'], // jp: 2552
        // Chainmail Zoro
        [ 2663, 5019, 'Roronoa Zoro, Three-Sword Style Swordsman in Chainmail'],
        // Chainmail Sanji
        [ 2664, 5020, 'Sanji, First Class Cook in Shining Armor'],
        // Raid Mihawk & Shanks
        [ 2818, 5023, "Shanks & Mihawk, Strong Swordsman's Reunion" ],
        [ 2819, 5024, "Shanks & Mihawk, World's Leading Two Swordsmen" ],
        // WB v2
        [ 2685, 5025, 'Whitebeard, Great Pirate Who Created an Age'],
        [ 2686, 5026, 'Whitebeard, Voiceless Rage'],
        // neo garp
        [ 3312, 5027, 'Monkey D. Garp [Neo]'], // JP 3312
        [ 3313, 5028, 'Garp the Fist [Neo]'], // JP 3313
        // Log Vivi v2
        [ 4988, 5029, 'Nefertari Vivi, Wake of an Endless Dream - Princess of Alabasta'],
        [ 4989, 5030, 'Nefertari Vivi, Wake of an Endless Dream - Pirate Queen'],
        // Log Ace v2
        [ 4990, 5031, 'Portgas D. Ace, Wake of an Endless Dream - Whitebeard Pirates'],
        [ 4991, 5032, 'Portgas D. Ace, Wake of an Endless Dream - High Seas Pirate'],
        // Neo Ivankov
        [ 3314, 5033, 'Emporio Ivankov [Neo]'], // JP 3314
        [ 3315, 5034, 'Emporio Ivankov [Neo], Queen of Kamabakka Queendom (Retired)'], // JP 3315
        // Neo Invasion WB
        [ 3316, 5035, 'Edward Newgate [Neo], Rival of the Pirate King'], // JP 3316
        [ 3317, 5036, 'Edward Newgate [Neo], Grand Pirate Whitebeard'], // JP 3317
        // support wanda
        [ 2772, 5037, 'Wanda: Kingsbird, Welcoming the Saviors'],
        // sanji/pudding
        [ 2919, 5038, 'Sanji & Pudding, Royal Matrimony'],
        // neo raid duval
        [ 3318, 5039, 'Iron Mask Duval [Neo]'], // JP 3318
        [ 3319, 5040, 'Duval [Neo], Flying Fish Riders Leader'], // JP 3319
        [ 3320, 5041, 'Duval [Neo], Rosy Life Riders Leader'], // JP 3320
        // neo nightmare luffy
        [ 3321, 5042, 'Monkey D. Luffy [Neo], Star of Hope'], // JP 3321
        [ 3322, 5043, 'Nightmare Luffy [Neo], Warrior of Hope'], // JP 3322
        // neo raid vergo
        [ 3323, 5044, 'Demon Bamboo Vergo [Neo]'], // JP 3323
        [ 3324, 5045, 'Demon Bamboo Vergo [Neo], Donquiote Family Senior Executive'], // JP 3324
        // summer pudding
        [ 4992, 5046, 'Charlotte Pudding, White Summer Sweets'],
        [ 4993, 5047, 'Charlotte Pudding, Devilish White Swimsuit'],
        // Sakura Akainu
        [ 2768, 5048, 'Akainu, Admirals in a Fleeting Moment of Calm'],
        [ 2769, 5049, 'Sakazuki, Admirals in a Fleeting Moment of Calm'],
        // ??
        [ 2770, 5050, 'Aokiji, Admirals in a Fleeting Moment of Calm'], // INT shooter fs
        [ 2771, 5051, 'Kuzan, Admirals in a Fleeting Moment of Calm'], // INT shooter fs
        // Raid Ussop & Chopper
        [ 3331, 5052, 'Usopp & Chopper, Ex-Weakling Duo'],
        // colo coby ex
        [ 4994, 5054, 'Coby [EXTRA], Navy HQ Petty Officer'],
        [ 4995, 5055, 'War Hero Coby [EXTRA], Navy HQ Petty Officer'],
        // helmeppo ex
        [ 4996, 5056, 'Sergeant Helmeppo [EXTRA]'],
        // support sengoku
        [ 4997, 5053, 'Sengoku, Fatherly Buddha'],
        // neo raid heracles
        [ 3325, 5057, 'Heracles-un [Neo]'], // JP 3325
        [ 3326, 5058, 'Heracles-un [Neo], Hero of the Forest'], // JP 3326
        // Condorinario GLB Invasion
        [ 3327, 5059, 'Condoriano'], // JP 3327
        // Aokiji v3
        [ 2929, 5060, 'Aokiji, Unyielding Beliefs of Justice' ],
        [ 2930, 5061, 'Kuzan, Unfaithful Beliefs of Justice' ],
        // Robin 6+
        [ 2830, 5062, 'Nico Robin, Umbrella Blocking Iron Stars' ],
        // wb v2 6+
        [ 2909, 5063, 'Whitebeard, End of the Long Journey' ],
        // Raid Law/Chopper
        [ 3330, 5064, 'Law & Chopper, Dynamic Doctor Duo' ],
        // Local Sea Monster
        [ 3383, 5065, 'Local Sea Monster, Man-Eating Monster' ],
        // Support Makino
        [ 3478, 5066, 'Makino, Proprietor of a Relaxed Tavern' ],
        // Akainu v3
        [ 3156, 5067, 'Akainu, Uncompromised Determination and Justice' ],
        [ 3157, 5068, 'Sakazuki, Uncompromised Determination and Justice' ],
    ];

    public static function unfakeName($optc_db_id)
    {
        $id = intval($optc_db_id);
        foreach (self::$unfake as $item) {
            if ($id == $item[0]) {
                return $item[2];
            }
        }
        return false;
    }

    public static function unfakeId($id)
    {
        $id = intval($id);

        foreach (self::$unfake as $item) {
            if ($id == $item[0]) {
                return $item[1];
            }
        }
        return $id;
    }

    public static function tentativeJpId($glb_id)
    {
        foreach (self::$unfake as $item) {
            if ($glb_id == $item[1]) {
                return $item[0];
            }
        }
        return null;
    }
}

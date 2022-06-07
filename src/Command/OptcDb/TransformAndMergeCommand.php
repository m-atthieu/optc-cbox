<?php

namespace App\Command\OptcDb;

use App\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\UnitFactory;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'optc-db:transform-and-merge',
    description: 'merge json files from the optc-db project in one file'
)]
class TransformAndMergeCommand extends Command
{
    private string $storage_dir;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->storage_dir = $kernel->getStorageDir();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $src_dir = "{$this->storage_dir}/optc-db";
        $units      = json_decode(file_get_contents("{$src_dir}/units.json"), true);
        $details    = json_decode(file_get_contents("{$src_dir}/details.json"), true);
        $evolutions = json_decode(file_get_contents("{$src_dir}/evolutions.json"), true);
        $families   = json_decode(file_get_contents("{$src_dir}/families.json"), true);
        $cooldowns  = json_decode(file_get_contents("{$src_dir}/cooldowns.json"), true);
        $flags      = json_decode(file_get_contents("{$src_dir}/flags.json"), true);
        $rumbles    = json_decode(file_get_contents("{$src_dir}/raw-rumble.json"), true);
        //$tiers      = json_decode(file_get_contents(APP_DIR . "/var/data/optc-fr/tiers.json"), true);

        $verbose = ($input->getOption('verbose') === true);

        $data = [];

        if ($verbose) {
            $output->writeln("[INFO] transforming units.json");
        }
        for ($i = 0; $i < count($units) && $i <= 4999; ++$i) {
            //var_dump($units[$i]);
            $unit = $units[$i];
            if ($unit[0] == '') {
                continue;
            }
            $id = UnitFactory::unfakeId($i + 1);
            $item = $this->transformUnit($id, $unit);
            /* glb exclusive units starts at line 3334 */
            if ($i > UnitFactory::GLB_EXCLUSIVE_LIMIT/*$id >= 5000*/) {
                $un_name = UnitFactory::unfakeName($i + 1);
                if ($un_name != $unit[0]) {
                    $output->writeln("<error>[ERROR] optc-db-id:" . ($i + 1) . " glb-id:{$id} {$un_name} != {$unit[0]}</error>");
                }
            }
            if (abs($id - $i) >= 2) {
                $un_name = UnitFactory::unfakeName($i + 1);
                if ($un_name != $unit[0]) {
                    $output->writeln("<error>[ERROR] optc-db-id:" . ($i + 1) . " glb-id:{$id} {$un_name} != {$unit[0]}</error>");
                }
            }
            /* end check glb exclusive */
            $data[$id] = $item;
        }

        if ($verbose) {
            $output->writeln("[INFO] transforming details.json");
        }
        foreach ($details as $id => $detail) {
            if (intval($id) >= 5000) {
                break;
            }
            $_id = UnitFactory::unfakeId(intval($id));
            $data[$_id]['details'] = $detail;
        }

        if ($verbose) {
            $output->writeln("[INFO] transforming evolutions.json");
        }
        foreach ($evolutions as $id => $evolution) {
            if (intval($id) >= 5000) {
                break;
            }
            $_id = UnitFactory::unfakeId(intval($id));
            $data[$_id]['evolutions'] = $evolution;
        }

        if ($verbose) {
            $output->writeln("[INFO] transforming flags.json");
        }
        foreach ($flags as $id => $flag) {
            if (intval($id) > 5000) {
                break;
            }
            $_id = UnitFactory::unfakeId(intval($id));
            if (isset($data[$_id]['id'])) {
                $data[$_id]['flags'] = $flag;
            } else {
                $output->writeln("<error>[ERROR] found flag " . json_encode($flag) . " at line {$id} but this unit doesn't exists</error>");
            }
        }

        if ($verbose) {
            $output->writeln("[INFO] transforming families.json");
        }
        foreach ($families as $id => $family) {
            if (intval($id) >= 5000) {
                break;
            }
            $_id = UnitFactory::unfakeId(intval($i));
            $data[$_id]['family'] = $family;
        }
        /*for($i = 0; $i < count($families) && $i < 4999; ++$i) {


           if(! isset($data[$_id])){
           if(! is_null($families[$i])){
           $output->writeln("<error>Families : found family ".json_encode($families[$i])." at line " . ($i + 1) . " but unit {$_id} is not registered</error>");
           }
           } else {
           $data[$_id]['family'] = $families[$i];
            }

           }*/

        if ($verbose) {
            $output->writeln("[INFO] transforming cooldowns.json");
        }
        for ($i = 0; $i < count($cooldowns) && $i < 4999; ++$i) {
            if (intval($id) >= 5000) {
                break;
            }
            $_id = UnitFactory::unfakeId($i + 1);
            if (! isset($data[$_id])) {
                if (! is_null($cooldowns[$i])) {
                    $output->writeln("<error>Cooldowns : found cooldown line " . ($i + 1) . " but unit {$_id} is not registered</error>");
                }
            } else {
                if (is_null($cooldowns[$i])) {
                    $output->writeln("<comment>Cooldowns : unit {$_id} has no cooldown</comment>");
                } else {
                    //var_dump($i, $cooldowns[$i]);
                    $data[$_id]['cooldowns'] = [
                        'min' => $cooldowns[$i][1],
                        'max' => $cooldowns[$i][0]
                    ];
                }
            }
        }

        /*if($verbose){ $output->writeln("[INFO] transforming tiers.json"); }
        foreach($tiers as $id => $tier){
            $_id = UnitFactory::unfakeId($id);
            if(! isset($data[$_id])){
                $output->writeln("<error>Tiers : found tiers for {$_id}({$id}) which is not registered</error>");
            } else {
                $data[$_id]['tiers'] = $tier;
            }
        }*/

        /* Rumble data is not needed for now, disabling */
        /*if ($verbose) {
            $output->writeln("[INFO] transforming rumble.json");
        }
        foreach ($rumbles['units'] as $rumble) {
            if (! array_key_exists('id', $rumble)) {
                echo "no id for rumble : " . print_r($rumble, true);
                continue;
            }
            $_id = UnitFactory::unfakeId($rumble['id']);
            if (! isset($data[$_id])) {
                $output->writeln("<error>Rumbe : found rumble for {$_id}({$id}) which is not registered</error>");
            } else {
                $data[$_id]['rumble'] = $rumble;
            }
        }*/

        file_put_contents("{$this->storage_dir}/units.json", json_encode(array_values($data), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }

    private function transformUnit($id, $data)
    {
        return [
            'id' => $id,
            'name' => $data[0],
            'type' => $data[1],
            'class' => is_array($data[2]) ? $data[2] : [$data[2]],
            'stars' => $data[3],
            'cost' => $data[4],
            'combo' => $data[5],
            'sockets' => $data[6],
            'max_lvl' => $data[7],
            'exp_to_max' => $data[8],
            'lvl_1_hp' => $data[9],
            'lvl_1_atk' => $data[10],
            'lvl_1_rcv' => $data[11],
            'max_hp' => $data[12],
            'max_atk' => $data[13],
            'max_rcv' => $data[14],
            'growth_rate' => $data[15]
        ];
    }

    private function flatten(array $array)
    {
        $return = array();
        array_walk_recursive($array, function ($a) use (&$return) {
            $return[] = $a;
        });
        return $return;
    }
}

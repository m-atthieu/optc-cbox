<?php

namespace App\Command\OptcDb;

use App\Kernel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'optc-db:convert-js-to-php',
    description: 'convert raw js files from the optc-db project to php'
)]
class ConvertJsToPhp extends Command
{
    private string $storage_dir;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->storage_dir = $kernel->getStorageDir();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $verbose = ($input->getOption('verbose') === false);

        $file = 'units';
        if ($verbose) {
            $output->writeln("[INFO] updating optc-db/{$file}.js");
        }
        $path = "{$this->storage_dir}/optc-db/raw-{$file}.js";
        $content = file_get_contents($path);
        $content_all = $this->transformUnitsCommon($content);
        $content_glb = $this->transformUnitsGlb($content);

        preg_match('/var calcGhostStartID = { "start": ([0-9]+) };/s', $content, $matches);
        $ghostStartId = intval($matches[1]);

        $filename = "{$this->storage_dir}/optc-db/{$file}-all.php";
        file_put_contents($filename, $content_all);
        $data_all = include($filename);
        $filename = "{$this->storage_dir}/optc-db/{$file}-glb.php";
        file_put_contents($filename, $content_glb);
        $data_glb = include($filename);

        for ($i = 0; count($data_all) < ($ghostStartId - count($data_glb) - 1); ++$i) {
                $data_all[] = [ '' ];
        }
        $data_all = array_merge($data_all, $data_glb);
        $json = "{$this->storage_dir}/optc-db/{$file}.json";
        file_put_contents($json, json_encode($data_all, JSON_PRETTY_PRINT));

        $file = 'details';
        if ($verbose) {
            $output->writeln("[INFO] updating optc-db/{$file}.js");
        }
        $path = "{$this->storage_dir}/optc-db/raw-{$file}.js";
        $content = file_get_contents($path);
        $content = $this->transformDetails($content);
        $filename = "{$this->storage_dir}/optc-db/{$file}.php";
        file_put_contents($filename, $content);
        $data = include($filename);
        $json = "{$this->storage_dir}/optc-db/{$file}.json";
        file_put_contents($json, json_encode($data, JSON_PRETTY_PRINT));
        //var_dump($details);

        $file = 'evolutions';
        if ($verbose) {
            $output->writeln("[INFO] updating optc-db/{$file}.js");
        }
        $path = "{$this->storage_dir}/optc-db/raw-{$file}.js";
        $content = file_get_contents($path);
        $content = str_replace('window.evolutions = ', '<?php return ', $content);
        $content = str_replace(['{', '}'], ['[', ']'], $content);
        $content = preg_replace('/^(\s*)([a-zA-Z]+)\s*:/m', '$1"$2":', $content);
        $content = str_replace(':', '=>', $content);
        $filename = "{$this->storage_dir}/optc-db/{$file}.php";
        file_put_contents($filename, $content);
        $data = include($filename);
        $json = "{$this->storage_dir}/optc-db/{$file}.json";
        file_put_contents($json, json_encode($data, JSON_PRETTY_PRINT));
        //var_dump($data);

        $file = 'cooldowns';
        if ($verbose) {
            $output->writeln("[INFO] updating optc-db/{$file}.js");
        }
        $path = "{$this->storage_dir}/optc-db/raw-{$file}.js";
        $content = file_get_contents($path);
        $content = preg_replace('/window\.[a-z]+ = /', '<?php return ', $content);
        $filename = "{$this->storage_dir}/optc-db/{$file}.php";
        file_put_contents($filename, $content);
        $data = include($filename);
        $json = "{$this->storage_dir}/optc-db/{$file}.json";
        file_put_contents($json, json_encode($data, JSON_PRETTY_PRINT));
        //var_dump($data);

        $file = 'families';
        if ($verbose) {
            $output->writeln("[INFO] updating optc-db/{$file}.js");
        }
        $path = "{$this->storage_dir}/optc-db/raw-{$file}.js";
        $content = file_get_contents($path);
        $content = preg_replace('/\(function\s?\(\)\s?\{/', '<?php ', $content);
        preg_match_all("/(const .*;)/", $content, $consts);
        preg_match("/window.families = (\{.*?\});/s", $content, $matches);
        //$am = preg_replace('/...([a-zA-Z]+)/', '$1', $matches[1]);
        $am = $matches[1];
        $am = str_replace(['{', '}'], ['[', ']'], $am);
        $am = str_replace(':', '=>', $am);
        $am = preg_replace('/([0-9]+)=>/', '"$1" =>', $am);
        $content = "<?php \n" . join("\n", $consts[0]) . "\n return {$am};";
        $filename = "{$this->storage_dir}/optc-db/{$file}.php";
        file_put_contents($filename, $content);
        $data = include($filename);
        $json = "{$this->storage_dir}/optc-db/{$file}.json";
        file_put_contents($json, json_encode($data, JSON_PRETTY_PRINT));
        //var_dump($data);

        $file = 'flags';
        if ($verbose) {
            $output->writeln("[INFO] updating optc-db/{$file}.js");
        }
        $path = "{$this->storage_dir}/optc-db/raw-{$file}.js";
        $content = file_get_contents($path);
        $content = preg_replace('/window\.[a-z]+ = /', '<?php return ', $content);
        $content = str_replace(['{', '}'], ['[', ']'], $content);
        $content = preg_replace('/([a-zA-Z]+)\s*:/m', '"$1":', $content);
        $content = str_replace(':', '=>', $content);
        $filename = "{$this->storage_dir}/optc-db/{$file}.php";
        file_put_contents($filename, $content);
        $data = include($filename);
        $json = "{$this->storage_dir}/optc-db/{$file}.json";
        file_put_contents($json, json_encode($data, JSON_PRETTY_PRINT));
        //var_dump($data);

        return self::SUCCESS;
    }

    public function transformUnitsCommon(string $content): string
    {
        preg_match("/window.units = (\[.*?\]);/s", $content, $matches);
        $content = "<?php return {$matches[1]};";
        return $content;
    }

    public function transformUnitsGlb(string $content): string
    {
        preg_match("/var globalExUnits = (\[.*?\]);/s", $content, $matches);
        $content = "<?php return {$matches[1]};";
        return $content;
    }

    public function transformDetails(string $content): string
    {
        $content = str_replace(['{', '}'], ['[', ']'], $content);
        $content = preg_replace('/^(\s*)([a-zA-Z0-9]+)\s*:/m', '$1"$2":', $content);
        $content = preg_replace([
            '/rAbility:/', '/captain:/', '/rSpecial:/', '/(\s+\[\s+)special:/', '/\s+base:/'], [
            '"rAbility":', '"captain":', '"rSpecial":', '$1"special":', ' "base":'], $content);
        $content = str_replace(':', '=>', $content);
        $content = preg_replace('/var calcGhostStartID = \["start"=> [0-9]+, "increment"=> [0-9+]\];/s', "\n", $content);
        preg_match("/window.details = (\[.*\]);/s", $content, $matches);

        //var_dump('details', $content, $matches);

        $content = "<?php return {$matches[1]};";
        return $content;
    }
}

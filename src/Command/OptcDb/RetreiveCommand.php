<?php

namespace App\Command\OptcDb;

use App\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'optc-db:retreive',
    description: 'retrieves raw js files from the optc-db project on GH'
)]
class RetreiveCommand extends Command
{
    private string $storage_dir;

    public function __construct(Kernel $kernel, private Filesystem $fs, private HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->storage_dir = $kernel->getStorageDir();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $base = 'https://raw.githubusercontent.com/optc-db/optc-db.github.io/master';
        $base = 'https://optc-db.github.io/';
        $url = array(
            'units'      => "{$base}/common/data/units.js",
            'details'    => "{$base}/common/data/details.js",
            'evolutions' => "{$base}/common/data/evolutions.js",
            'cooldowns'  => "{$base}/common/data/cooldowns.js",
            'families'   => "{$base}/common/data/families.js",
            'drops'      => "{$base}/common/data/drops.js",
            'flags'      => "{$base}/common/data/flags.js",
            'rumble'     => "{$base}/common/data/rumble.json",
            'utils'      => "{$base}/common/js/utils.js",
        );

        $dest_dir = "{$this->storage_dir}/optc-db";
        if (! $this->fs->exists($dest_dir)) {
            $this->fs->mkdir($dest_dir, 0777, true);
        }

        foreach ($url as $key => $value) {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $response = $this->httpClient->request('GET', $value);
            $content = $response->getContent();
            file_put_contents("{$dest_dir}/raw-{$key}.{$ext}", $content);
        }

        return self::SUCCESS;
    }
}

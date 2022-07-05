<?php

namespace App\Command\Debug;

use App\Repository\UnitRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'debug:units',
    description: 'dump units list'
)]
class Units extends Command
{
    public function __construct(private UnitRepository $units)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $units = $this->units->findAll();
        $content = json_encode($units, JSON_PRETTY_PRINT);
        $output->write($content);

        return self::SUCCESS;
    }
}

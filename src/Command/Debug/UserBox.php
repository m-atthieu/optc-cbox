<?php

namespace App\Command\Debug;

use App\Repository\UnitRepository;
use App\Repository\UserBoxRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'debug:userbox',
    description: 'dump user box'
)]
class UserBox extends Command
{
    public function __construct(private UserBoxRepository $repository)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $object = $this->repository->findOneByUserId('391245463');
        $content = json_encode($object, JSON_PRETTY_PRINT);
        $output->write($content);

        return self::SUCCESS;
    }
}

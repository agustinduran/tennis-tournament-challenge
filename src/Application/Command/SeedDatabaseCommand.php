<?php

namespace App\Application\Command;

use App\Application\Service\Seed\SeedGenderService;
use App\Application\Service\Seed\SeedTournamentService;
use App\Application\Service\Seed\SeedPlayerService;
use App\Application\Service\Seed\SeedPlayerPropertyService;
use App\Application\Service\Seed\SeedPlayerPropertyValueService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedDatabaseCommand extends Command
{
    protected static $defaultName = 'app:seed-database';

    private SeedGenderService $seedGenderService;
    private SeedTournamentService $seedTournamentService;
    private SeedPlayerService $seedPlayerService;
    private SeedPlayerPropertyService $seedPlayerPropertyService;
    private SeedPlayerPropertyValueService $seedPlayerPropertyValueService;

    public function __construct(
        SeedGenderService $seedGenderService,
        SeedTournamentService $seedTournamentService,
        SeedPlayerService $seedPlayerService,
        SeedPlayerPropertyService $seedPlayerPropertyService,
        SeedPlayerPropertyValueService $seedPlayerPropertyValueService
    ) {
        parent::__construct();
        $this->seedGenderService = $seedGenderService;
        $this->seedTournamentService = $seedTournamentService;
        $this->seedPlayerService = $seedPlayerService;
        $this->seedPlayerPropertyService = $seedPlayerPropertyService;
        $this->seedPlayerPropertyValueService = $seedPlayerPropertyValueService;
    }

    protected function configure()
    {
        $this->setDescription('Seed the database with rows');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->seedGenderService->execute();
        $this->seedTournamentService->execute();
        $this->seedPlayerService->execute();
        $this->seedPlayerPropertyService->execute();
        $this->seedPlayerPropertyValueService->execute();

        $output->writeln('Database seeded successfully.');

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Service\AuthorService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:emptyAuthorsDelete',
    description: 'Add a short description for your command',
)]
class EmptyAuthorsDeleteCommand extends Command
{
    public function __construct(private AuthorService $authorService)
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('looking for authors without books...');
        $this->authorService->deleteAuthorsWithoutBooks();
        $output->writeln('deleted them all!');
        return Command::SUCCESS;
    }
}

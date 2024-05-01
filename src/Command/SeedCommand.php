<?php

namespace App\Command;

use App\Service\AuthorService;
use App\Service\BooksService;
use App\Service\PublisherService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;

#[AsCommand(
    name: 'app:seed',
    description: 'fills database with books,publishers and authors',
)]
class SeedCommand extends Command
{
    public function __construct(private AuthorService $authorService,
                                private BooksService $booksService,
                                private PublisherService $publisherService)
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $generator = Factory::create();
        $output->writeln('seeding database');
        for ($i = 0; $i < 10; $i++) {
            $request = new Request(['name'=>$generator->city,'address'=>$generator->address]);
            $publisher = $this->publisherService->createPublisher($request);
            unset($request);
            $request = new Request(['name'=>$generator->name(),'surname'=>$generator->lastName]);
            $authors = new ArrayCollection();
            $author = $this->authorService->createAuthor($request);
            $authors->add($author);
            unset($request);
            $request = new Request(['name'=>$generator->name(),'year'=>rand(1980,2024)]);
            if ($i>5){
                continue;
            }
            $this->booksService->createBook($request,$authors,$publisher);
        }
        $output->writeln('database seeded');

        return Command::SUCCESS;
    }
}

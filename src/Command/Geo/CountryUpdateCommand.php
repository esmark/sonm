<?php

declare(strict_types=1);

namespace App\Command\Geo;

use App\Entity\Geo\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * https://ru.wikipedia.org/wiki/ISO_3166-1
 *
 * @todo грабить с вики по коду страны
 */
class CountryUpdateCommand extends Command
{
    protected static $defaultName = 'app:geo:country-update';

    private $io;
    private $em;
    private $kernel;

    protected function configure()
    {
        $this
            ->setDescription('Обновление стран')
        ;
    }

    public function __construct(EntityManagerInterface $em, KernelInterface $kernel)
    {
        parent::__construct();

        $this->em     = $em;
        $this->kernel = $kernel;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;

        $country = $em->getRepository(Country::class)->findOneBy(['iso_code_alpha_3' => 'RUS']);

        if ($country) {
            $this->io->text('RUS уже есть');
        } else {
            $country = new Country();
            $country
                ->setEngname('Russia')
                ->setOffname('Россия')
                ->setIsoCodeAlpha2('RU')
                ->setIsoCodeAlpha3('RUS')
                ->setIsoCodeNumeric(643)
                ->setNameCanonical(mb_strtolower('Россия'))
            ;

            $em->persist($country);
            $em->flush();

            $this->io->text('RUS создана');
        }

        return 0;
    }
}

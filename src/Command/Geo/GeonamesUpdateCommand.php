<?php

declare(strict_types=1);

namespace App\Command\Geo;

use App\Entity\Geo\Abbreviation;
use App\Entity\Geo\City;
use App\Entity\Geo\Province;
use App\Entity\Geo\Region;
use App\Entity\Geo\Settlement;
use App\Entity\Geo\Street;
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
 * Замеченные нестыковки:
 *
 * куйбышев -> Samara
 * горячий ключ -> Melchu Khe
 * каменка -> Khal-Kiloy
 * ростов -> Rostov-na-Donu
 * нальчик -> Nalchik Airport
 */
class GeonamesUpdateCommand extends Command
{
    protected static $defaultName = 'app:geo:geonames-update';

    /** @var SymfonyStyle */
    private $io;
    private $em;
    private $kernel;
    private $startTime;
    private $count = 0;

    protected function configure()
    {
        $this
            ->setDescription('Обновление геоданных из базы GeoNames Gazetteer')
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

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start(md5(__FILE__));
        $this->startTime = microtime(true);

        $count = 0;
        $em = $this->em;

        if (!file_exists($this->kernel->getProjectDir().'/var/geonames-RU.txt')) {
            $this->io->error('Файл /var/geonames-RU.txt отсутсвует');

            return 0;
        }

        $handle = fopen($this->kernel->getProjectDir().'/var/geonames-RU.txt', 'r');

        $i = 0;
        while (($buffer = fgets($handle, 4096)) !== false) {
            $i++;

            $row = explode("\t", $buffer);

            if (!isset($row[6]) or $row[6] !== 'P') {
                if (!isset($row[7]) or $row[7] == 'PPLH' or $row[7] == 'PPLQ' or $row[7] == 'PPLCH') {
                    continue;
                }
            }

            $citiesNames = explode(',', $row[3]);

            if (count($citiesNames) < 13) {
                continue;
            }

            foreach ($citiesNames as $cityName) {
                $cities = $this->em->getRepository(City::class)->findBy(['name_canonical' => trim(mb_strtolower($cityName))]);

                if (count($cities) == 1) {
                    $city = $cities[0];

                    if (empty($city->getLatitude())) {
                        $count++;
                        $this->io->text($i . '. ' . "$count => Город: '{$city->getOffname()}' КОД: {$row[7]}");

                        $city
                            ->setEngname($row[1])
                            ->setLatitude((float) $row[4])
                            ->setLongitude((float) $row[5])
                            ->setTimezone($row[17])
                        ;

                        $em->flush();
                    }
                } elseif (count($cities) > 1) {
                    //$this->io->text($i . '. ' ."В БД найдено несколько городов '$cityName'");

                    continue 2;
                } else {
                    // город не найден в БД - перебор дальше
                }
            }
        }

        if (!feof($handle)) {
            echo "fgets() неожиданно потерпел неудачу\n";
        }

        fclose($handle);

        $event = $stopwatch->stop(md5(__FILE__));

        $this->io->comment(sprintf('Items processed: %d / Elapsed time: %.4f s / Consumed memory: %.2f MB',
            $count,
            microtime(true) - $this->startTime,
            $event->getMemory() / (1024 ** 2)
        ));

        return 0;
    }
}

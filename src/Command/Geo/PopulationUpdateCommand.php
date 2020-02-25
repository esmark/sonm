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

class PopulationUpdateCommand extends Command
{
    protected static $defaultName = 'app:geo:population-update';

    /** @var SymfonyStyle */
    private $io;
    private $em;
    private $kernel;
    private $startTime;
    private $count = 0;

    protected function configure()
    {
        $this
            ->setDescription('Обновление данных о численности населения городов')
        ;
    }

    public function __construct(EntityManagerInterface $em, KernelInterface $kernel)
    {
        parent::__construct();

        if(!function_exists('dbase_open')) {
            require_once $kernel->getProjectDir().'/src/Utils/DBase.php';
        }

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

        $categories = [
            'Малые города – до 50 тысяч жителей' => [
                'selector' => 'div.common-text ol > li',
                'url' => 'http://xn----7sbiew6aadnema7p.xn--p1ai/reytin-cities.php?name=%D0%BC%D0%B0%D0%BB%D1%8B%D0%B5'
            ],
            'Средние города – до 100 тысяч жителей' => [
                'selector' => 'div.common-text ol > li',
                'url' => 'http://xn----7sbiew6aadnema7p.xn--p1ai/reytin-cities.php?name=%D1%81%D1%80%D0%B5%D0%B4%D0%BD%D0%B8%D0%B5'
            ],
            'Большие города – более 100 тысяч жителей' => [
                'selector' => '#text-l ol > li',
                'url' => 'http://xn----7sbiew6aadnema7p.xn--p1ai/reytin-cities.php?name=%D0%B1%D0%BE%D0%BB%D1%8C%D1%88%D0%B8%D0%B5'
            ],
            'Крупные города – более 250 тысяч жителей' => [
                'selector' => '#text-l ol > li',
                'url' => 'http://xn----7sbiew6aadnema7p.xn--p1ai/reytin-cities.php?name=%D0%BA%D1%80%D1%83%D0%BF%D0%BD%D1%8B%D0%B5'
            ],
            'Крупнейшие города – от 500 тысяч до 1 миллиона жителей' => [
                'selector' => '#text-l ol > li',
                'url' => 'http://xn----7sbiew6aadnema7p.xn--p1ai/reytin-cities.php?name=%D0%BA%D1%80%D1%83%D0%BF%D0%BD%D0%B5%D0%B9%D1%88%D0%B8%D0%B5'
            ],
            'Города-миллионеры – более 1000000 жителей' => [
                'selector' => '#text-l ol > li',
                'url' => 'http://xn----7sbiew6aadnema7p.xn--p1ai/reytin-cities.php?name=%D0%BC%D0%B8%D0%BB%D0%BB%D0%B8%D0%BE%D0%BD%D0%B5%D1%80%D1%8B'
            ],
        ];

        foreach ($categories as $name => $category) {
            $this->io->note("Парсинг: " . $name);

            $client = HttpClient::create();
            $response = $client->request('GET', $category['url']);
            $crawler = new Crawler($response->getContent());

            $crawler->filter($category['selector'])->each(function (Crawler $node, $i) {
                $cityName = $node->filter('a')->text('');

                if (empty($cityName)) {
                    $cityName = $node->filter('strong')->text('');

                    if (false !== mb_stripos($cityName, '(')) {
                        $cityName = mb_substr($cityName, 0, mb_stripos($cityName, '('));
                        $cityName = trim($cityName, " \t\n\r\0\x0B\xc2\xa0");
                    }

                    if ($cityName == 'Лысково') {
                        return 0;
                    }
                }

                $cityName = trim($cityName, " \t\n\r\0\x0B\xc2\xa0");

                if ($cityName === 'Орел') {
                    $cityName = 'Орёл';
                }

                $i++;

                $population = str_replace(['Численность населения', 'Количество жителей'], '', $node->filter('span')->text('', $normalizeWhitespace = false));
                $population = str_replace([' '], '', $population);

                Try_City_Name_With_E:

                $city = $this->em->getRepository(City::class)->findBy(['name_canonical' => trim(mb_strtolower($cityName))]);

                if (count($city) == 1) {
                    $city = $city[0];
                } elseif (count($city) > 1) {
                    $regionName = $node->filter('strong')->text('');
                    $regionName = mb_substr($regionName, mb_strlen($cityName) + 1);
                    $regionName = str_replace([' (', '( ', '(', ') ', '​)', ')'], '', $regionName);
                    $regionName = str_replace([' '], ' ', $regionName);

                    if (0 === mb_stripos($regionName, 'Республика')) {
                        $regionName = str_replace('Республика ', '', $regionName);
                        $regionName .= ' Республика';
                    }

                    if (trim(mb_strtolower($regionName)) === 'кемеровская область') {
                        $regionName = 'Кемеровская область - Кузбасс Область';
                    } elseif (trim(mb_strtolower($regionName)) === 'сахаякутия республика') {
                        $regionName = 'саха /якутия/ республика';
                    } elseif (trim(mb_strtolower($regionName)) === 'ханты-мансийский автономный округ - югра') {
                        $regionName = 'ханты-мансийский автономный округ - югра автономный округ';
                    }

                    $regionName = trim($regionName, " \t\n\r\0\x0B\xc2\xa0");

                    $region = $this->em->getRepository(Region::class)->findOneBy(['fullname_canonical' => mb_strtolower($regionName)]);

                    if ($region) {
                        $city = $this->em->getRepository(City::class)->findOneBy([
                            'name_canonical' => trim(mb_strtolower($cityName)),
                            'region' => $region,
                        ]);
                    } else {
                        $this->io->text($i . '. ' ."'$regionName' - регион для города '$cityName' не найден в БД!");

                        return 0;
                    }
                } else {
                    if (false !== mb_stripos($cityName, 'ё')) {
                        $cityNameOriginal = $cityName;
                        $cityName = str_replace('ё', 'е', $cityName);

                        //$this->io->text($i . '. ' . "'$cityNameOriginal' - Не найден в БД, пробуем найти '$cityName'");

                        goto Try_City_Name_With_E;
                    }

                    $this->io->text($i . '. ' . "'$cityName' - Не найден в БД!");
                }

                if ($city) {
//                    $this->io->text($i . '. ' . $cityName . "\t$population\t" . $city->getOffname());
                    $this->count++;

                    if ($city->getPopulation() != (int) $population) {
                        $city->setPopulation((int) $population);

                        $this->em->flush();
                    }
                } else {
//                    $this->io->text($i . '. ' . $cityName);
                }
            });

            $wait = rand(10, 40);

            $this->io->text("--- ожидание $wait сек. перед следующим запросом");

            sleep($wait);
        }

        $count = $this->count;
        $event = $stopwatch->stop(md5(__FILE__));

        $this->io->comment(sprintf('Items processed: %d / Elapsed time: %.4f s / Consumed memory: %.2f MB',
            $count,
            microtime(true) - $this->startTime,
            $event->getMemory() / (1024 ** 2)
        ));

        return 0;
    }
}

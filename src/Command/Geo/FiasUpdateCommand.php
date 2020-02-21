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
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class FiasUpdateCommand extends Command
{
    protected static $defaultName = 'app:geo:fias-update';

    /** @var SymfonyStyle */
    private $io;
    private $em;
    private $kernel;
    /** @var int */
    protected $startTime;

    protected function configure()
    {
        $this
            ->setDescription('Загрузка данных из файлов DBF в БД')
            //->addArgument('region', InputArgument::REQUIRED, 'Номер региона')
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
     */
    protected function __interact(InputInterface $input, OutputInterface $output)
    {
        $region = $input->getArgument('region');
        if (null !== $region) {
            $this->io->text(' > <info>Region</info>: '.$region);
        } else {
            $region = $this->io->ask('Region');
            $input->setArgument('region', $region);
        }
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
        $fiasDir = $this->kernel->getProjectDir() . '/var/fias';

//        $region = $input->getArgument('region');

        if (!is_dir($fiasDir)) {
            $this->io->error('Папка отсутсвует: '.$fiasDir);

            return 0;
        }

        if (file_exists($fiasDir.'/SOCRBASE.DBF')) {
            $this->io->writeln('Импорт: SOCRBASE.DBF');

            $db = dbase_open($fiasDir.'/SOCRBASE.DBF', DBASE_RDONLY);

            for ($i = 1; $i <= dbase_numrecords($db); $i++) {
                $rec = dbase_get_record_with_names($db, $i);

                $abbreviation = $em->getRepository(Abbreviation::class)->findOneBy(['code' => $rec['KOD_T_ST']]);

                if (empty($abbreviation)) {
                    $abbreviation = new Abbreviation();
                    $abbreviation
                        ->setCode((int) $rec['KOD_T_ST'])
                        ->setFullname(mb_convert_encoding($rec['SOCRNAME'], 'UTF-8', 'CP-866'))
                        ->setShortname(mb_convert_encoding($rec['SCNAME'], 'UTF-8', 'CP-866'))
                        ->setLevel((int) $rec['LEVEL'])
                    ;

                    $em->persist($abbreviation);
                    $em->flush();
                }
            }

            dbase_close($db);
        } else {
            $this->io->error('Файл SOCRBASE.DBF отсутсвует');

            return 0;
        }

        $finder = new Finder();
        $files = $finder
            ->ignoreDotFiles(true)
            ->in($fiasDir)
            ->name('ADDROB*.DBF')
        ;

        if ($files->count()) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($files as $file) {
                $size = round($file->getSize() / 1024 / 1024, 2);

                $this->io->writeln('Импорт: ' . $file->getFilename() . ' (' . $size . 'Mb)');

                //$db = \App\Utils\dbase_open($file->getPathname(), DBASE_RDONLY);
                $db = dbase_open($file->getPathname(), DBASE_RDONLY);

                $regions = [];
                $areas       = 0;
                $cities      = 0;
                $settlements = 0; // населённые пункты
                $streets = [];
                $buildings = [];

                $dbFlushCount = 0;
                for ($i = 1; $i <= dbase_numrecords($db); $i++) {
                    $rec = dbase_get_record_with_names($db, $i);

                    if (!empty(trim($rec['NEXTID']))
                        or $rec['LIVESTATUS'] == 0
                        or $rec['ACTSTATUS'] == 0
                        or $rec['deleted'] == 1
                    ) {
                        continue;
                    }

                    if ($dbFlushCount++ > 500) {
                        $em->flush();
                        $dbFlushCount = 0;
                    }

                    $count++;

                    // Регионы
                    if ($rec['AOLEVEL'] == 1) {
                        $regions++;

                        $region = $em->getRepository(Region::class)->findOneBy(['aoid' => $rec['AOID']]);

                        if (empty($region)) {
                            $region = $this->factoryRegion($rec);

                            $em->persist($region);
                            $em->flush();

                            // Если в БД фиас регион является городом, то добавить эту запись ещё и в таблицу City.
                            if ($region->getShortname() == 'г') {
                                $city = $em->getRepository(City::class)->findOneBy(['aoid' => $rec['AOID']]);

                                if (empty($city)) {
                                    $city = $this->factoryCity($rec);

                                    $em->persist($city);
                                }
                            }
                        }

                        continue;
                    }

                    // Районы
                    if ($rec['AOLEVEL'] == 3) {
                        $areas++;

                        $province = $em->getRepository(Province::class)->findOneBy(['aoid' => $rec['AOID']]);

                        if (empty($province)) {
                            $province = $this->factoryProvince($rec);

                            $em->persist($province);
                            //$em->flush();
                        }

                        continue;
                    }

                    // Города
                    if ($rec['AOLEVEL'] == 4) {
                        $cities++;

                        $city = $em->getRepository(City::class)->findOneBy(['aoid' => $rec['AOID']]);

                        if (empty($city)) {
                            $city = $this->factoryCity($rec);

                            $em->persist($city);
                            $em->flush();
                        }

                        continue;
                    }

                    // Населённые пункты
                    if ($rec['AOLEVEL'] == 6) {
                        $settlements++;

                        $settlement = $em->getRepository(Settlement::class)->findOneBy(['aoid' => $rec['AOID']]);

                        if (empty($settlement)) {
                            $settlement = $this->factorySettlement($rec);

                            $em->persist($settlement);
                            //$em->flush();
                        }

                        continue;
                    }

                    // Улицы
                    if ($rec['AOLEVEL'] == 7777777) {
                        $streets++;

                        $street = $em->getRepository(Street::class)->findOneBy(['aoid' => $rec['AOID']]);

                        if (empty($street)) {
                            $street = $this->factoryStreet($rec);

                            $em->persist($street);
                            //$em->flush();
                        }

                        continue;
                    }

                    if ($rec['AOLEVEL'] == 8) {
                        //$buildings[$i] = $rec;

                        continue;
                    }
                }

                dbase_close($db);

                dump($count);
                dump('provinces = '.$areas);
                dump('cities = '.$cities);
                dump('settlements = '.$settlements);
//                dump('streets = '.count($streets));
//                dump('buildings = '.count($buildings));

//                dump('flushing...');
                $em->flush();
//                dump('OK !');
            }
        } else {
            $this->io->error("В папке {$fiasDir} отсутсвуют файлы ADDROB*.DBF");
        }

        $event = $stopwatch->stop(md5(__FILE__));

        if ($output->isDebug()) {
            $this->io->comment(
                sprintf(
                    'Items processed: %d / Elapsed time: %.4f s / Consumed memory: %.2f MB',
                    $count,
                    microtime(true) - $this->startTime,
                    $event->getMemory() / (1024 ** 2)
                )
            );
        }

        return 0;
    }

    /**
     * @param array $rec
     *
     * @return City
     */
    protected function factoryCity(array $rec): City
    {
        $em = $this->em;

        $abbreviation = $em->getRepository(Abbreviation::class)->findOneBy([
            'level' => 4,
            'shortname' => mb_convert_encoding(trim($rec['SHORTNAME']), 'UTF-8', 'CP-866')
        ]);
        $province = $em->getRepository(Province::class)->findOneBy(['areacode' => $rec['AREACODE']]);
        $region = $em->getRepository(Region::class)->findOneBy(['regioncode' => $rec['REGIONCODE']]);

        $city = new City();
        $city
            ->setAbbreviation($abbreviation)
            ->setRegion($region)
            ->setRegioncode($rec['REGIONCODE'])
            ->setAoid($rec['AOID'])
            ->setAoguid($rec['AOGUID'])
            ->setAreacode($rec['AREACODE'])
            ->setCentstatus((int) $rec['CENTSTATUS'])
            ->setCitycode($rec['CITYCODE'])
            ->setPlaincode($rec['PLAINCODE'])
            ->setIfnsfl($rec['IFNSFL'])
            ->setIfnsul($rec['IFNSUL'])
            ->setOkato($rec['OKATO'])
            ->setOktmo($rec['OKTMO'])
            ->setPostalcode($rec['POSTALCODE'])
            ->setTerrifnsfl($rec['TERRIFNSFL'])
            ->setTerrifnsul($rec['TERRIFNSUL'])
            ->setNameCanonical(mb_strtolower(mb_convert_encoding(trim($rec['OFFNAME']), 'UTF-8', 'CP-866')))
            ->setOffname(mb_convert_encoding($rec['OFFNAME'], 'UTF-8', 'CP-866'))
            ->setFormalname(mb_convert_encoding($rec['FORMALNAME'], 'UTF-8', 'CP-866'))
            ->setShortname(mb_convert_encoding($rec['SHORTNAME'], 'UTF-8', 'CP-866'))
        ;

        if ($province) {
            $city->setProvince($province);
        }

        return $city;
    }

    /**
     * @param array $rec
     *
     * @return Region
     */
    protected function factoryRegion(array $rec): Region
    {
        $em = $this->em;

        $abbreviation = $em->getRepository(Abbreviation::class)->findOneBy([
            'level' => 1,
            'shortname' => mb_convert_encoding(trim($rec['SHORTNAME']), 'UTF-8', 'CP-866')
        ]);

        $fullname = mb_convert_encoding(trim($rec['OFFNAME']), 'UTF-8', 'CP-866') . ' ' . $abbreviation->getFullname();

        $region = new Region();
        $region
            ->setAbbreviation($abbreviation)
            ->setRegioncode($rec['REGIONCODE'])
            ->setPlaincode($rec['PLAINCODE'])
            ->setAoid($rec['AOID'])
            ->setAoguid($rec['AOGUID'])
            ->setIfnsfl($rec['IFNSFL'])
            ->setIfnsul($rec['IFNSUL'])
            ->setOkato($rec['OKATO'])
            ->setOffname(mb_convert_encoding($rec['OFFNAME'], 'UTF-8', 'CP-866'))
            ->setFormalname(mb_convert_encoding($rec['FORMALNAME'], 'UTF-8', 'CP-866'))
            ->setFullname($fullname)
            ->setFullnameCanonical(mb_strtolower($fullname))
            ->setShortname(mb_convert_encoding($rec['SHORTNAME'], 'UTF-8', 'CP-866'))
        ;

        return $region;
    }

    /**
     * @param array $rec
     *
     * @return Province
     */
    protected function factoryProvince(array $rec): Province
    {
        $em = $this->em;

        $abbreviation = $em->getRepository(Abbreviation::class)->findOneBy([
            'level' => 3,
            'shortname' => mb_convert_encoding(trim($rec['SHORTNAME']), 'UTF-8', 'CP-866')
        ]);
        $region = $em->getRepository(Region::class)->findOneBy(['regioncode' => $rec['REGIONCODE']]);

        $province = new Province();
        $province
            ->setAbbreviation($abbreviation)
            ->setRegion($region)
            ->setRegioncode($rec['REGIONCODE'])
            ->setAoid($rec['AOID'])
            ->setAoguid($rec['AOGUID'])
            ->setAreacode($rec['AREACODE'])
            ->setPlaincode($rec['PLAINCODE'])
            ->setIfnsfl($rec['IFNSFL'])
            ->setIfnsul($rec['IFNSUL'])
            ->setOkato($rec['OKATO'])
            ->setTerrifnsfl($rec['TERRIFNSFL'])
            ->setTerrifnsul($rec['TERRIFNSUL'])
            ->setNameCanonical(mb_strtolower(mb_convert_encoding(trim($rec['OFFNAME']), 'UTF-8', 'CP-866')))
            ->setOffname(mb_convert_encoding($rec['OFFNAME'], 'UTF-8', 'CP-866'))
            ->setFormalname(mb_convert_encoding($rec['FORMALNAME'], 'UTF-8', 'CP-866'))
            ->setShortname(mb_convert_encoding($rec['SHORTNAME'], 'UTF-8', 'CP-866'))
        ;

        return $province;
    }

    /**
     * @param array $rec
     *
     * @return Settlement
     */
    protected function factorySettlement(array $rec): Settlement
    {
        $em = $this->em;

        $abbreviation = $em->getRepository(Abbreviation::class)->findOneBy([
            'level' => 6,
            'shortname' => mb_convert_encoding(trim($rec['SHORTNAME']), 'UTF-8', 'CP-866')
        ]);
        $province     = $em->getRepository(Province::class)->findOneBy(['areacode' => $rec['AREACODE']]);
        $region       = $em->getRepository(Region::class)->findOneBy(['regioncode' => $rec['REGIONCODE']]);
        $city         = $em->getRepository(City::class)->findOneBy(['citycode' => $rec['CITYCODE']]);

        $settlement = new Settlement();
        $settlement
            ->setAbbreviation($abbreviation)
            ->setRegion($region)
            ->setRegioncode($rec['REGIONCODE'])
            ->setAoid($rec['AOID'])
            ->setAoguid($rec['AOGUID'])
            ->setAreacode($rec['AREACODE'])
            ->setCentstatus((int) $rec['CENTSTATUS'])
            ->setCitycode($rec['CITYCODE'])
            ->setPlaincode($rec['PLAINCODE'])
            ->setPlacecode($rec['PLACECODE'])
            ->setIfnsfl($rec['IFNSFL'])
            ->setIfnsul($rec['IFNSUL'])
            ->setOkato($rec['OKATO'])
            ->setOktmo($rec['OKTMO'])
            ->setPostalcode($rec['POSTALCODE'])
            ->setTerrifnsfl($rec['TERRIFNSFL'])
            ->setTerrifnsul($rec['TERRIFNSUL'])
            ->setNameCanonical(mb_strtolower(mb_convert_encoding(trim($rec['OFFNAME']), 'UTF-8', 'CP-866')))
            ->setOffname(mb_convert_encoding($rec['OFFNAME'], 'UTF-8', 'CP-866'))
            ->setFormalname(mb_convert_encoding($rec['FORMALNAME'], 'UTF-8', 'CP-866'))
            ->setShortname(mb_convert_encoding($rec['SHORTNAME'], 'UTF-8', 'CP-866'))
        ;

        if ($province) {
            $settlement->setProvince($province);
        }

        if ($city) {
            $settlement->setCity($city);
        }

        return $settlement;
    }

    /**
     * @param array $rec
     *
     * @return Street
     */
    protected function factoryStreet(array $rec): Street
    {
        $em = $this->em;

        $abbreviation = $em->getRepository(Abbreviation::class)->findOneBy([
            'level' => 7,
            'shortname' => mb_convert_encoding(trim($rec['SHORTNAME']), 'UTF-8', 'CP-866')
        ]);
        $province   = $em->getRepository(Province::class)->findOneBy(['areacode' => $rec['AREACODE']]);
        $region     = $em->getRepository(Region::class)->findOneBy(['regioncode' => $rec['REGIONCODE']]);
        $city       = $em->getRepository(City::class)->findOneBy(['citycode' => $rec['CITYCODE']]);
        $settlement = $em->getRepository(Settlement::class)->findOneBy(['placecode' => $rec['PLACECODE']]);

        $street = new Street();
        $street
            ->setAbbreviation($abbreviation)
            ->setRegion($region)
            ->setRegioncode($rec['REGIONCODE'])
            ->setAoid($rec['AOID'])
            ->setAoguid($rec['AOGUID'])
            ->setAreacode($rec['AREACODE'])
            ->setCitycode($rec['CITYCODE'])
            ->setPlaincode($rec['PLAINCODE'])
            ->setPlacecode($rec['PLACECODE'])
            ->setIfnsfl($rec['IFNSFL'])
            ->setIfnsul($rec['IFNSUL'])
            ->setOkato($rec['OKATO'])
            ->setOktmo($rec['OKTMO'])
            ->setTerrifnsfl($rec['TERRIFNSFL'])
            ->setTerrifnsul($rec['TERRIFNSUL'])
            ->setNameCanonical(mb_strtolower(mb_convert_encoding(trim($rec['OFFNAME']), 'UTF-8', 'CP-866')))
            ->setOffname(mb_convert_encoding($rec['OFFNAME'], 'UTF-8', 'CP-866'))
            ->setFormalname(mb_convert_encoding($rec['FORMALNAME'], 'UTF-8', 'CP-866'))
            ->setShortname(mb_convert_encoding($rec['SHORTNAME'], 'UTF-8', 'CP-866'))
        ;

        if ($province) {
            $street->setProvince($province);
        }

        if ($city) {
            $street->setCity($city);
        }

        if ($settlement) {
            $street->setSettlement($settlement);
        }

        return $street;
    }
}

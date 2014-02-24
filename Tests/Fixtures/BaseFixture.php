<?php

namespace Giosh94mhz\GeonamesBundle\Tests\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Entity\Feature;
use Giosh94mhz\GeonamesBundle\Entity\Toponym;

class BaseFixture /* implements Doctrine\Common\DataFixtures\FixtureInterface */
{
    private $om;

    private $features;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->om = $manager;

        $this->loadFeatures();

        $this->loadToponyms();

        $manager->flush();
    }

    protected function loadFeatures()
    {
        $fixtures = array(
            array('A.ADM1', 'first-order administrative division'),
            array('A.ADM1H', 'historical first-order administrative division'),
            array('A.ADM2', 'second-order administrative division'),
            array('A.ADM2H', 'historical second-order administrative division'),
            array('A.ADM3', 'third-order administrative division'),
            array('A.ADM3H', 'historical third-order administrative division'),
            array('A.ADM4', 'fourth-order administrative division'),
            array('A.ADM4H', 'historical fourth-order administrative division'),
            array('A.ADM5', 'fifth-order administrative division'),
            array('A.ADMD', 'administrative division'),
            array('A.ADMDH', 'historical administrative division '),
            array('A.LTER', 'leased area'),
            array('A.PCL', 'political entity'),
            array('A.PCLD', 'dependent political entity'),
            array('A.PCLF', 'freely associated state'),
            array('A.PCLH', 'historical political entity'),
            array('A.PCLI', 'independent political entity'),
            array('A.PCLIX', 'section of independent political entity'),
            array('A.PCLS', 'semi-independent political entity'),
            array('P.PPL', 'populated place'),
            array('P.PPLA', 'seat of a first-order administrative division'),
            array('P.PPLA2', 'seat of a second-order administrative division'),
            array('P.PPLA3', 'seat of a third-order administrative division'),
            array('P.PPLA4', 'seat of a fourth-order administrative division'),
            array('P.PPLC', 'capital of a political entity'),
            array('P.PPLCH', 'historical capital of a political entity'),
            array('P.PPLF', 'farm village'),
            array('P.PPLG', 'seat of government of a political entity'),
            array('P.PPLH', 'historical populated place'),
            array('P.PPLL', 'populated locality'),
            array('P.PPLQ', 'abandoned populated place'),
            array('P.PPLR', 'religious populated place'),
            array('P.PPLS', 'populated places'),
            array('P.PPLW', 'destroyed populated place'),
            array('P.PPLX', 'section of populated place'),
            array('P.STLMT', 'israeli settlement'),
            array('R.CSWY', 'causeway')
        );

        foreach ($fixtures as $f) {
            $this->features[$f[0]] = $feature = new Feature(substr($f[0], 0, 1), substr($f[0], 2));
            $feature->setName($f[1]);
            $this->om->persist($feature);
        }
    }

    protected function loadToponyms()
    {
        $fixtures = array(
            array(3175395, 'Repubblica Italiana', 'Repubblica Italiana', 42.83333, 12.83333, 'A.PCLI', 'IT', '00', null, null, null, 60340328, null),
            array(3174618, 'Regione Lombardia', 'Regione Lombardia', 45.66667, 9.5, 'A.ADM1', 'IT', '09', null, null, null, 9826141, null),
            array(3169070, 'Rome', 'Rome', 41.89474, 12.4839, 'P.PPLC', 'IT', '07', 'RM', '058091', null, 2563241, null),
            array(3173435, 'Milano', 'Milano', 45.46427, 9.18951, 'P.PPLA', 'IT', '09', 'MI', '015146', null, 1306661, '120'),
            array(3178227, 'Provincia di Como', 'Provincia di Como', 45.98333, 9.21667, 'A.ADM2', 'IT', '09', 'CO', null, null, 590050, null),
            array(6535698, 'Ello', 'Ello', 45.78568, 9.36534, 'P.PPLA3', 'IT', '09', 'LC', '097033', null, 1110, null),
            array(6537155, 'Romano di Lombardia', 'Romano di Lombardia', 45.52458, 9.74836, 'A.ADM3', 'IT', '09', 'BG', '016183', null, 18622, null),
        );

        foreach ($fixtures as $f) {
            $toponym = new Toponym($f[0]);
            $toponym
                ->setName($f[1])
                ->setAsciiName($f[2])
                ->setLatitude($f[3])
                ->setLongitude($f[4])
                ->setFeature($this->features[$f[5]])
                ->setCountryCode($f[6])
                ->setAdmin1Code($f[7])
                ->setAdmin2Code($f[8])
                ->setAdmin3Code($f[9])
                ->setAdmin4Code($f[10])
                ->setPopulation($f[11])
                ->setElevation($f[12])
                ->setLastModify(new \DateTime())
            ;
            $this->om->persist($toponym);
        }
    }
}

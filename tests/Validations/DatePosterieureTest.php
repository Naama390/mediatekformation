<?php

namespace App\tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Test sur l'entree d'une date pour etre sur qu'elle est anterieure a la date du jour
 *
 * @author Naama Blum
 */
class DatePosterieureTest extends KernelTestCase
{
    public function getFormation(): Formation{
        return (new Formation())
                ->setTitle("test sur formation")
                ->setPublishedAt(new DateTime("2022/01/13"));
    }

    public function testDateAnterieure()
    {
        $formation = $this->getFormation()->setPublishedAt(new DateTime("tomorrow"));
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount(1, $error);
    }
    
    public function assertErrors(Formation $formation, int $nbErreursAttendues)
    {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}

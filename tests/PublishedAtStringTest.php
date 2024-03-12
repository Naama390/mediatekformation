<?php

namespace App\tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Test sur le fonctionnement de la méthode qui retourne la date de parution au format string.
 *
 * @author Naama Blum
 */
class PublishedAtStringTest extends TestCase
{
    public function testGetPublishedAtString(){
       $formation = new Formation();
       $formation->setPublishedAt(new DateTime("2024-02-12"));
       $this->assertEquals("12/02/2024", $formation->getPublishedAtString());
    }
}

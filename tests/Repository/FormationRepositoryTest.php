<?php

namespace App\tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test sur toutes les methodes de FormationRepository
 *
 * @author Naama Blum
 */
class FormationRepositoryTest extends KernelTestCase
{
    /**
     * Recupere le repository de Formation
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer() -> get(FormationRepository::class);
        return $repository;
    }
    
    /**
      * Creation d'une instance de Formation avec titre, description, et date de publication
      * @return Formation
      */
    public function newFormation(): Formation
    {
        $formation = (new formation())
                ->setTitle("Formation de test")
                ->setDescription("Test d'ajout de formation")
                ->setPublishedAt(new DateTime("now"));
        return $formation;
    }
    
    /**
     * Fonction de test d'ajout d'une formation
     */
    public function testAjoutFormation()
    {
        $repository = $this->recuprepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations +1, $repository->count([]), "erreur lors de l'ajout de la formation");
    }
    
    /**
     * Fonction de test de suppression d'une formation
     */
    public function testSuppressionFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations -1, $repository->count([]), "erreur lors de la suppression de la formation");
    }
    
    /**
     * Fonction de test de tri des formations sur un champ selon un ordre
     */
    public function testFindAllOrderBy()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderBy("title", "ASC");
        $nbFormations = count($formations);
        $this->assertEquals(239, $nbFormations);
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment",
                $formations[0]->getTitle());
    }
    
    /**
     * Fonction de test de tri des formations sur un champ d'une autre table selon un ordre
     */
    public function testFindAllOrderByTable()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderBy("name", "DESC", "playlist");
        $nbFormations = count($formations);
        $this->assertEquals(237, $nbFormations);
        $this->assertEquals("C# : ListBox en couleur", $formations[0]->getTitle());
    }
    
    /**
     * Fonction de test qui filtre les formations dont un champ contient une valeur donnée
     */
    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "eclipse");
        $nbFormations = count($formations);
        $this->assertEquals(9, $nbFormations);
        $this->assertEquals("Eclipse n°8 : Déploiement", $formations[0]->getTitle());
    }
    
    /**
     * Fonction de test qui filtre les formations dont un champ d'une autre table contient une valeur donnée
     */
    public function testFindByContainValueTable()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("name", "Visual Studio", "playlist");
        $nbFormations = count($formations);
        $this->assertEquals(11, $nbFormations);
        $this->assertEquals("C# : ListBox en couleur", $formations[0]->getTitle());
    }
    
    /**
     * Fonction de test d'affichage des dernieres formations publiees
     */
    public function testFindAllLasted()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(1);
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEqualsWithDelta(new DateTime("now"), $formations[0]->getPublishedAt(), 1);
    }
    
    /**
     * Fonction de test d'affichage des formations d'une playlist
     */
    public function testFindAllForOnePlaylist()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllForOnePlaylist(1);
        $nbFormations = count($formations);
        $this->assertEquals(8, $nbFormations);
        $this->assertEquals("Eclipse n°1 : installation de l'IDE", $formations[0]->getTitle());
    }
}

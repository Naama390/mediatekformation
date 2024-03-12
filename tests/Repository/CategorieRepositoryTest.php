<?php

namespace App\tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test sur toutes les methodes de CategorieRepository
 *
 * @author Naama Blum
 */
class CategorieRepositoryTest extends KernelTestCase
{
    /**
     * Recupere le repository de Categorie
     * @return CategorieRepository
     */
    public function recupRepository(): CategorieRepository
    {
        self::bootKernel();
        $repository = self::getContainer() -> get(CategorieRepository::class);
        return $repository;
    }
    
    /**
     * Creation d'une instance de categorie avec nom
     * @return Categorie
     */
    public function newCategorie(): Categorie
    {
        $categorie = (new categorie())
                ->setName("Categorie de test");
        return $categorie;
    }
    
    /**
     * Teste de la methode d'ajout d'une categorie
     */
    public function testAjoutCategorie ()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout de la categorie");
    }
    
    /**
     * Teste la methode de suppression d'une categorie
     */
    public function testSuppressionCategorie()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression de la categorie");
    }
    
    /**
     * Teste la methode qui retourne les categories des formations associees a une playlist
     */
    public function testFindAllForOne()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllForOnePlaylist(3);
        $nbCategories = count($categories);
        $this->assertEquals(2, $nbCategories);
        $this->assertEquals("POO", $categories[0]->getName());
    }
    
    /**
     * Teste la methode qui retourne la liste des categories dont le nom est le meme que le valeur entree
     */
    public function testFindAllWithFilterName()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllWithFilterName("UML");
        $nbCategories = count($categories);
        $this->assertEquals(1, $nbCategories);
        $this->assertEquals("UML", $categories[0]->getName());
    }
}

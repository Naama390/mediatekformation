<?php

namespace App\tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test sur toutes les methodes de PlaylistRepository
 *
 * @author Naama Blum
 */
class PlaylistRepositoryTest extends KernelTestCase
{
    /**
     * Recupere le repository de Playlist
     * @return PlaylistRepository
     */
    public function recupRepository(): PlaylistRepository
    {
        self::bootKernel();
        $repository = self::getContainer() -> get(PlaylistRepository::class);
        return $repository;
    }
    
    /**
     * Creation d'une instance de Playlist avec nom et description
     * @return Playlist
     */
    public function newPlaylist(): Playlist
    {
        $playlist = (new Playlist())
                ->setName("Playlist de test")
                ->setDescription("Test d'ajout de playlist");
        return $playlist;
    }
    
    /**
     * Fonction de test d'ajout d'une playlist
     */
    public function testAjoutPlaylist()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository ->add($playlist, true);
        $this->assertEquals($nbPlaylists +1, $repository->count([]), "erreur lors de l'ajour de la playlist");
    }
    
    /**
     * Fonction de test de suppression d'une playlist
     */
    public function testSuppressionPlaylist()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository ->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository ->Remove($playlist, true);
        $this->assertEquals($nbPlaylists -1, $repository->count([]), "erreur lors de la suppression de la playlist");
    }
    
    /**
     * Fonction de test qui affiche les playlists par nom de playlist selon un ordre
     */
    public function testFindAllOrderByName()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByName("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(28, $nbPlaylists);
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }
    
    /**
     * Fonction de test qui affiche les playlists par nb de formations
     */
    public function testFindAllOrderByNbFormation()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByNbFormation("DESC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(28, $nbPlaylists);
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }
    
    /**
     * Fonction test qui filtre les playlists dont un champ contient une valeur donnée
     */
    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue("name", "Python");
        $nbPlaylists = count($playlists);
        $this->assertEquals(1, $nbPlaylists);
        $this->assertEquals("Programmation sous Python", $playlists[0]->getName());
    }
    
        /**
     * Fonction de test qui filtre les playlists d'une autre table dont un champ contient une valeur donnée
     */
    public function testFindByContainValueTable()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue("name", "Java", "categorie");
        $nbPlaylists = count($playlists);
        $this->assertEquals(3, $nbPlaylists);
        $this->assertEquals("Eclipse et Java", $playlists[0]->getName());
    }
}

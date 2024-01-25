<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

define("PAGEADMINPLAYLIST", "admin/admin.playlists.html.twig");

class AdminPlaylistsController extends AbstractController
{
    /**
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * Creation du constructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     * @param PlaylistRepository $playlistRepository
     */
    public function __construct(FormationRepository $formationRepository,
            CategorieRepository $categorieRepository, PlaylistRepository $playlistRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response
    {
        $playlists= $this->playlistRepository->findAllOrderByName("ASC");
        $categories=$this->categorieRepository->findAll();
        return $this->render(PAGEADMINPLAYLIST, [
                'playlists'=> $playlists,
                'categories'=> $categories
                ]);
    }
    
    /**
     * Suppression d'une playlist
     * @Route("/admin/playlists/delete/{id}", name="admin.delete.playlists")
     * @param Playlist $playlists
     * @return Response
     */
    public function delete(Playlist $playlists): Response
    {
        $this->paylistRepository->remove($playlists, true);
        return $this->redirectToRoute("admin.playlists");
    }
    
    /**
     * Edition d'une playlist
     * @Route("/admin/edit.playlists/{id}", name="admin.edit.playlists")
     * @param Playlist $playlists
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $playlists, Request $request): Response
    {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlists);
        
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this-> playlistRepository-> add($playlists, true);
            return $this->redirectToRoute("admin.playlists");
        }
        
        return $this-> render("admin/admin.edit.playlists.html.twig", [
            'playlists' => $playlists,
            'formplaylist' => $formPlaylist ->createView()
        ]);
    }
    
    /**
     * Ajout d'une playlist
     * @route("/admin/playlists/add", name="admin.add.playlists")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $playlists = new Playlist();
        $formPlaylist = $this -> createForm(PlaylistType::class, $playlists);
        
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this-> playlistRepository-> add($playlists, true);
            $this-> addFlash("success", "La playlist a été ajoutée avec succès.");
            return $this->redirectToRoute("admin.playlists");
        }
        
        return $this->render("admin/admin.add.playlists.html.twig", [
            'playlists' => $playlists,
            'formplaylist' => $formPlaylist ->createView()
        ]);
    }
    
    /**
     * Tri des playlists
     * @route("/admin/playlists/sort/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response
    {
        switch($champ){
        case"name":
            $playlists= $this-> playlistRepository->findAllOrderByName($ordre);
            break;
        case"nbformations":
            $playlists= $this-> playlistRepository-> findAllOrderByNbFormation($ordre);
            break;
        }
        $categories= $this-> categorieRepository->findAll();
        return $this-> render(PAGEADMINPLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
    /**
     * Recherche par filtrage
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(PAGEADMINPLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

}

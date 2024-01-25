<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

define("PAGEADMINFORMATION", "admin/admin.formations.html.twig");

class AdminFormationsController extends AbstractController
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
     * Creation du constructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response
    {
        $formations= $this->formationRepository->findAllOrderBy("title", "ASC");
        $categories=$this->categorieRepository->findAll();
        return $this->render(PAGEADMINFORMATION, [
                'formations'=> $formations,
                'categories'=> $categories
                ]);
    }
    
    /**
     * Suppression d'une formation
     * @Route("/admin/delete/{id}", name="admin.delete.formations")
     * @param Formation $formations
     * @return Response
     */
    public function delete(Formation $formations): Response
    {
        $this->formationRepository->remove($formations, true);
        return $this->redirectToRoute("admin.formations");
    }
    
    /**
     * Edition d'une formation
     * @Route("/admin/edit/{id}", name="admin.edit.formations")
     * @param Formation $formations
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formations, Request $request): Response
    {
        $formFormation = $this->createForm(FormationType::class, $formations);
        
        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this-> formationRepository-> add($formations, true);
            return $this->redirectToRoute("admin.formations");
        }
        
        return $this-> render("admin/admin.edit.formations.html.twig", [
            'formations' => $formations,
            'formformation' => $formFormation ->createView()
        ]);
    }
    
    /**
     * Ajout d'une formation
     * @route("/admin/add", name="admin.add.formations")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $formations = new Formation();
        $formFormation = $this -> createForm(FormationType::class, $formations);
        
        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this-> formationRepository-> add($formations, true);
            $this-> addFlash("success", "La formation a été ajouté avec succès.");
            return $this->redirectToRoute("admin.formations");
        }
        
        return $this->render("admin/admin.add.formations.html.twig", [
            'formations' => $formations,
            'formformation' => $formFormation ->createView()
        ]);
    }
    
    /**
     * Tri des formations
     * @route("/admin/formations/sort/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response
    {
        $formations= $this-> formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories= $this-> categorieRepository->findAll();
        
        return $this-> render(PAGEADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * Recherche par filtrage
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(PAGEADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
}

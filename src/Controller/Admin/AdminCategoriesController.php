<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author Naama Blum
 */
class AdminCategoriesController extends AbstractController
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
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response
    {
        $formations= $this->formationRepository->findAll();
        $categories=$this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
                'formations'=> $formations,
                'categories'=> $categories
                ]);
    }
    
    /**
     * Suppression d'une catégorie
     * @Route("/admin/categories/delete/{id}", name="admin.delete.categories")
     * @param Categorie $categories
     * @return Response
     */
    public function delete(Categorie $categories): Response
    {
        $this->categorieRepository->remove($categories, true);
        return $this->redirectToRoute("admin.categories");
    }
    
    /**
     * Ajout d'une catégorie
     * @route("/admin/categories/add", name="admin.add.categories")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $name= $request-> get("name");
        $listcategories= $this-> categorieRepository->findAllWithFilterName($name);
        
        if ($listcategories == false) {
            $categories= new Categorie ();
            $categories-> setName($name);
            $this-> categorieRepository-> add($categories, true);
            $this-> addFlash("success", "La catégorie a été ajoutée avec succès.");
            return $this->redirectToRoute("admin.categories");
        }else{
            $this->addFlash("error", "La catégorie existe déjà.");
        }
        
        return $this->redirectToRoute("admin.categories");
    }
}

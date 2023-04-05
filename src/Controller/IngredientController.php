<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * This function is used to display the list of ingredients
     * 
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/ingredient', name: 'ingredient', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), 
            10 
        );
        
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * This function is used to add a new ingredient
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/ingredient/new', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response 
    {   
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'L\'ingrédient a bien été ajouté');
            return $this->redirectToRoute('ingredient');
        }
        
        return $this->render('pages/ingredient/new.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * This function is used to edit an ingredient
     * 
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager) : Response
    {   
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'L\'ingrédient a bien été modifé');
            return $this->redirectToRoute('ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
            
    }

    /**
     * This function is used to delete an ingredient
     * 
     * @param EntityManagerInterface $manager
     * @param Ingredient $ingredient
     * @return Response
     */

    #[Route('/ingredient/delete/{id}', name: 'ingredient.delete', methods: ['GET','POST'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {   
        if(!$ingredient) {
            $this->addFlash('danger', 'L\'ingrédient n\'existe pas');
        }
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash('success', 'L\'ingrédient a bien été supprimé');
        return $this->redirectToRoute('ingredient');
    }
}
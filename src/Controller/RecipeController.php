<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    /**
     * This function is used to display the list of recipes
     * 
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/recipe', name: 'recipe', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * This function is used to create a new recipe
     * 
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/recipe/new', name: 'recipe.new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'La recette a bien été ajoutée');

            return $this->redirectToRoute('recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to edit a recipe
     * 
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Security('is_granted("ROLE_USER") and user === recipe.getUser()', message: "Vous n'avez pas accès à cette ressource")]
    #[Route('/recipe/edit/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'La recette a bien été modifé');
            return $this->redirectToRoute('recipe');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This function is used to display the list of public recipes
     * 
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/recipe/public', name: 'recipe.index.public', methods: ['GET'])]
    public function indexPublic(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['isPublic' => true]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/recipe/index_public.html.twig', [
            'recipes' => $recipes
        ]);

    }

    /**
     * This function is used to display a recipe if it is public
     * 
     * @param Recipe $recipe
     * @return Response
     */

    #[Security('is_granted("ROLE_USER") and recipe.getIsPublic() === true', message: "Vous n'avez pas accès à cette ressource")]
    #[Route('/recipe/{id}', name: 'recipe.show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }


    /**
     * This function is used to delete a recipe
     * 
     * @param EntityManagerInterface $manager
     * @param Recipe $recipe
     * @return Response
     */

    #[Route('/recipe/delete/{id}', name: 'recipe.delete', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, Recipe $recipe): Response
    {
        if (!$recipe) {
            $this->addFlash('danger', 'La recette n\'existe pas');
        }
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash('success', 'La recette a bien été supprimé');
        return $this->redirectToRoute('recipe');
    }
}

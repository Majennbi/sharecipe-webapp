<?php

namespace App\Controller\Admin;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Contact;
use App\Entity\Ingredient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sharecipe - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Recipe', 'fas fa-bowl-rice', Recipe::class);
        yield MenuItem::linkToCrud('Mark', 'fa-solid fa-ranking-star', Mark::class);
        yield MenuItem::linkToCrud('Ingredient', 'fas fa-utensils', Ingredient::class);
        yield MenuItem::linkToCrud('Contact', 'fa-solid fa-envelope-circle-check', Contact::class);
    }
}

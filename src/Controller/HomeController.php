<?php 

namespace App\Controller;

use App\Controller\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index()
    {
        return $this->render('home.index.html.twig', [
        ]);
    }
    
}

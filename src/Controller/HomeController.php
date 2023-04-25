<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Controller\CategoryController;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function homeIndex(CategoryRepository $categoryRepository, CategoryController $categoryController): Response
    {
        // Récupération de toutes les catégories de la table

        $request = $categoryController->index($categoryRepository);
        $response = $request->getContent();
        $myData = json_decode($response);
        // var_dump($cate);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'myData' => $myData
        ]);
    }
}

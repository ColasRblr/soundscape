<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Controller\CategoryController;
use App\Repository\CategoryRepository;
use App\Repository\FavoriteRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function homeIndex(
        CategoryRepository $categoryRepository,
        CategoryController $categoryController,
        Security $security
    ): Response {

        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }

        // Récupération de toutes les catégories de la table
        $request = $categoryController->index($categoryRepository);
        $response = $request->getContent();
        $myData = json_decode($response);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'myData' => $myData, 'isUserConnected' => $isUserConnected, 'roleUser' => $roleUser
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function admin_index(CategoryRepository $categoryRepository, FavoriteController $favoriteController, FavoriteRepository $favoriteRepository, Security $security): Response

    {

        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }
        // Récupération de toutes les catégories de la table
        $categories = $categoryRepository->findAll();

        // Construction de la liste d'objets à partir des données récupérées
        $myData = [];
        foreach ($categories as $category) {
            $myData[] = [
                "categoryId" => $category->getId(),
                "categoryPath" => $category->getImage(),
                "categoryName" => $category->getName(),
                "categoryAlt" => $category->getName(),
            ];
        }

        $topFavorites = $favoriteRepository->mostFavorites();
        // var_dump($topFavorites);
        return $this->render('home/admin.html.twig', [
            'controller_name' => 'HomeController', 'myData' => $myData, 'topFavorites' => $topFavorites, 'isUserConnected' => $isUserConnected, 'roleUser' => $roleUser
        ]);
    }

}

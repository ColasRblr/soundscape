<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CategoryController;
use App\Repository\FavoriteRepository;
use Symfony\Component\Security\Core\Security;


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

        // $isUserConnected = ($security->getUser() === null) ? false : true;

        // if ($isUserConnected) {
        //     $roleUser = ($security->getUser()->getRoles() == "ROLE_USER") ? true : false;
        // } else {
        //     $roleUser = "";
        // }
        var_dump($roleUser);

        // Récupération de toutes les catégories de la table
        $request = $categoryController->index($categoryRepository);
        $response = $request->getContent();
        $myData = json_decode($response);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'myData' => $myData, 'isUserConnected' => $isUserConnected, 'roleUser' => $roleUser
        ]);
    }
    #[Route('/admin', name: 'app_admin')]
    public function admin_index(CategoryRepository $categoryRepository, FavoriteController $favoriteController, FavoriteRepository $favoriteRepositor, Security $security): Response

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
        var_dump($topFavorites);
        return $this->render('home/admin.html.twig', [
            'controller_name' => 'HomeController', 'myData' => $myData, 'topFavorites' => $topFavorites

        ]);
    }


    // #[Route('/admin', name: 'app_admin')]
    // public function index_admin(): Response
    // {
    //     return $this->render('home/admin.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }

    #[Route('/admin_song', name: 'app_admin_song')]
    public function index_admin_song(): Response
    {
        return $this->render('home/admin_song.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category')]
class CategoryController extends AbstractController
{
    public function index(CategoryRepository $categoryRepository): Response
    {
        $cate = $categoryRepository->findAll();

        // Construction du tableau à partir des données récupérées
        $myData = [];
        foreach ($cate as $cat) {
            $myData[] = [
                "categoryId" => $cat->getId(),
                "categoryPath" => $cat->getImage(),
                "categoryName" => $cat->getName(),
                "categoryAlt" => $cat->getName(),
            ];
        }
        return $this->json($myData);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    
    public function new(Request $request, CategoryRepository $categoryRepository, Security $security): Response
    {
        $isUserConnected = false;
        $roleUser = '';


        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $idCategory = $request->get('id_category');

        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Définir l'ID de l'utilisateur connecté pour la relation 'user' de la catégorie
        $category->setUser($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'id_category' => $idCategory, 'isUserConnected' => $isUserConnected, 'roleUser' => $roleUser
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository, Security $security): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            'isUserConnected' => $isUserConnected, 'roleUser' => $roleUser
        ]);
    }

    #[Route('/{id}/delete', name: 'app_category_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository, Security $security): Response
    {
        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_admin', [
            'isUserConnected' => $isUserConnected, 'roleUser' => $roleUser
        ], Response::HTTP_SEE_OTHER);
    }

    // SOUNDSCAPE METHODS 
    #[Route('/{id}/getsongs', name: 'get_songs_by_category', methods: ['GET'])]
    public function getSongsByCategory(Category $category, CategoryController $categoryController, CategoryRepository $categoryRepository,): Response
    {
        $songs = $category->getSongs();
        $data = array();
        foreach ($songs as $song) {
            $data[] = array(
                'id' => $song->getId(),
                'title' => $song->getTitle(),
                'artist' => $song->getArtist(),
            );
        }

        dump($songs); // Vérifiez si la liste de chansons est vide ou non
        return $this->json($data);
    }
}

<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
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
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $idCategory = $request->get('id_category');

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'id_category' => $idCategory,
        ]);
    }

    // #[Route('/admin', name: 'app_category_show', methods: ['GET'])]
    // public function show(Category $category): Response
    // {
    //     return $this->render('category/show.html.twig', [
    //         'category' => $category,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
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

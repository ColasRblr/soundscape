<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongType;
use App\Repository\SongRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/song')]
class SongController extends AbstractController
{
    #[Route('/', name: 'app_song_index', methods: ['GET'])]
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('song/index.html.twig', [
            'songs' => $songRepository->findAll(),
            // 'category' => $categoryRepository->findCategoryId(),
        ]);
    }

    #[Route('/category/{categoryId}', name: 'app_song_by_category_index', methods: ['GET'])]
    public function songByCategory(int $categoryId, SongRepository $songRepository, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($categoryId);
        $songs = $songRepository->findBy(['category' => $category]);
    
        return $this->render('song/index.html.twig', [
            'songs' => $songs,
            'category' => $category,
        ]);
    }
    

    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SongRepository $songRepository): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $songRepository->save($song, true);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('song/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    ////////////////////
    #[Route('/{id}', name: 'app_song_show', methods: ['GET'])]
    public function show(Song $song): Response
    {
        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    // #[Route('/', name: 'app_song_index', methods: ['GET'])]
    // public function showSongByCategoryId(SongRepository $songRepository, $category_id)
    // {
    //     $song = $songRepository->find($category_id);

    //     // Créer un tableau associatif avec les données de la chanson
    //     $songData = [
    //         'image' => $song->getImage(),
    //         'title' => $song->getTitle(),
    //         'artist' => $song->getArtist(),
    //         'url' => $song->getUrl(),
    //     ];

    //     // Retourner les données de la chanson au format JSON
    //     return $this->json($songData);
    // }

    // #[Route('/{category_id}', name: 'app_song_show_by_category', methods: ['GET'])]
    // public function show_by_category(Song $song): Response
    // {
    //     return $this->render('song/show.html.twig', [
    //         'song' => $song,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_song_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Song $song, SongRepository $songRepository): Response
    {
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $songRepository->save($song, true);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('song/edit.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_song_delete', methods: ['POST'])]
    public function delete(Request $request, Song $song, SongRepository $songRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $song->getId(), $request->request->get('_token'))) {
            $songRepository->remove($song, true);
        }

        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    }

    // SOUNDSCAPE METHODS 

    public function showSongById(SongRepository $songRepository, $id)
    {
        $song = $songRepository->find($id);

        // Créer un tableau associatif avec les données de la chanson
        $songData = [
            'image' => $song->getImage(),
            'title' => $song->getTitle(),
            'artist' => $song->getArtist(),
            'url' => $song->getUrl(),
            'id' => $song->getId()
        ];

        // Retourner les données de la chanson au format JSON
        return $this->json($songData);
    }
    #[Route('/home/admin_song', name: 'admin_song')]
    public function adminSongPage(
        CategoryRepository $categoryRepository,
        CategoryController $categoryController,

    ): Response {
        // Récupération de toutes les catégories de la table

        $request = $categoryController->getSongsByCategory($categoryRepository);
        $response = $request->getContent();
        $myData = json_decode($response);
        var_dump($myData);

        return $this->render('home/admin_song.html.twig', [
         'myData' => $myData
        ]);
    }
}


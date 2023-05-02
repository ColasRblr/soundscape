<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongType;
use App\Repository\SongRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/song')]
class SongController extends AbstractController
{

    #[Route('/byCategory/{categoryId}', name: 'app_song_by_category_index', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function adminSongPage(int $categoryId, SongRepository $songRepository, CategoryRepository $categoryRepository, Security $security): Response

    {
        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }
        $category = $categoryRepository->find($categoryId);
        $songs = $songRepository->findBy(['category' => $category]);

        return $this->render('song/index.html.twig', [
            'songs' => $songs,
            'category' => $category,
            'isUserConnected' => $isUserConnected,
            'roleUser' => $roleUser
        ]);
    }

    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, SongRepository $songRepository, Security $security): Response
    {

        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }

        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Définir l'ID de l'utilisateur connecté pour la relation 'user' de la catégorie
        $song->setUser($user);


        if ($form->isSubmitted() && $form->isValid()) {
            $songRepository->save($song, true);

            $categoryId = $song->getCategory()->getId();
            return $this->redirectToRoute('app_song_by_category_index', ['categoryId' => $categoryId], Response::HTTP_SEE_OTHER);
        }
        return $this->render('song/new.html.twig', [
            'song' => $song,
            'form' => $form,
            'isUserConnected' => $isUserConnected,
            'roleUser' => $roleUser
        ]);
    }

    #[Route('/{id}/edit', name: 'app_song_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(Request $request, Song $song, SongRepository $songRepository, Security $security): Response
    {
        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $songRepository->save($song, true);
            $categoryId = $song->getCategory()->getId();
            return $this->redirectToRoute('app_song_by_category_index', ['categoryId' => $categoryId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('song/edit.html.twig', [
            'song' => $song,
            'form' => $form,
            'isUserConnected' => $isUserConnected,
            'roleUser' => $roleUser
        ]);
    }

    #[Route('/{id}', name: 'app_song_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Song $song, SongRepository $songRepository, Security $security): Response
    {
        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }
        if ($this->isCsrfTokenValid('delete' . $song->getId(), $request->request->get('_token'))) {
            $songRepository->remove($song, true);
        }

        $categoryId = $song->getCategory()->getId();
        return $this->redirectToRoute('app_song_by_category_index', ['categoryId' => $categoryId], Response::HTTP_SEE_OTHER);
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
}

<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Form\FavoriteType;
use App\Repository\SongRepository;
use App\Repository\FavoriteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/favorite')]
class FavoriteController extends AbstractController
{
    #[Route('/', name: 'app_favorite_index', methods: ['GET'])]
    public function index(FavoriteRepository $favoriteRepository, Security $security): Response
    {

        $user = $security->getUser();

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favoriteRepository->findByFavoriteUserId($user),
        ]);
    }

    #[Route('/{id}', name: 'app_favorite_show', methods: ['GET'])]
    public function show(Favorite $favorite): Response
    {
        return $this->render('favorite/show.html.twig', [
            'favorite' => $favorite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_favorite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favorite $favorite, FavoriteRepository $favoriteRepository): Response
    {
        $form = $this->createForm(FavoriteType::class, $favorite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $favoriteRepository->save($favorite, true);

            return $this->redirectToRoute('app_favorite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favorite/edit.html.twig', [
            'favorite' => $favorite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favorite_delete', methods: ['POST'])]
    public function delete(Request $request, Favorite $favorite, FavoriteRepository $favoriteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $favorite->getId(), $request->request->get('_token'))) {
            $favoriteRepository->remove($favorite, true);
        }

        return $this->redirectToRoute('app_favorite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_favorite_player', methods: ['POST'])]
    public function favoritePlayer(FavoriteRepository $favoriteRepository, Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favoriteRepository->findByCategoryId($user),
        ]);

        return $this->redirectToRoute('app_favorite_index', [], Response::HTTP_SEE_OTHER);
    }

    // SOUNDSCAPE METHODS

    #[Route('/newFavorite/{id}', name: 'app_new_favorite')]
    public function newFavorite(Security $security, FavoriteRepository $favoriteRepository, SongRepository $songRepository, $id, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        $song = $songRepository->find($id);

        // Vérifier si la chanson est déjà dans les favoris de l'utilisateur
        $favorite = $favoriteRepository->findOneBy(['user' => $user, 'song' => $song]);

        // Si la chanson est déjà dans les favoris, la supprimer
        if ($favorite) {
            $favoriteRepository->remove($favorite, true);
        } else {
            // Sinon, ajouter la chanson aux favoris
            $favorite = new Favorite();
            $favorite->setSong($song);
            $favorite->setUser($user);
            $favoriteRepository->save($favorite, true);
        }
        // Rediriger l'utilisateur vers sa page d'origine
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    #[Route('/isFavorite/{id}', name: 'app_is_favorite')]
    public function isFavorite(SongRepository $songRepository, Security $security, FavoriteRepository $favoriteRepository, $id): JsonResponse
    {
        $user = $security->getUser();
        $song = $songRepository->find($id);
        $favorite = $favoriteRepository->findOneBy(['user' => $user, 'song' => $song]);

        if ($favorite) {
            return new JsonResponse("true");
        } else {
            return new JsonResponse("false");
        }
    }
}

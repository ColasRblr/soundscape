<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\Security\Core\Security;

#[Route("/player")]
class PlayerController extends AbstractController
{

    #[Route("/{id}/{category_id}", name: "app_player_show")]
    public function showSong($id, $category_id, Security $security)
    {
        // Appeler la méthode showSongById du SongController pour récupérer les données de la chanson
        $response = $this->forward(SongController::class . '::showSongById', [
            'id' => $id,
        ]);

        $isUserConnected = false;
        $roleUser = '';
        if ($security->getUser() != null) {
            $isUserConnected = true;
            $roleUser = $security->getUser()->getRoles();
        }
        // Récupérer les données de la chanson à partir de la réponse JSON
        $songData = $response->getContent();
        $song = json_decode($songData);

        // Afficher la vue du player avec les données de la chanson
        return $this->render('player/player.html.twig', [
            'song' => $song,
            'category_id' => $category_id,
            'isUserConnected' => $isUserConnected,
            'roleUser' => $roleUser,
            'isUserConnected' => $isUserConnected,
            'roleUser' => $roleUser
        ]);
    }
}

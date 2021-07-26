<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Controller;

use LotGD\Core\Exceptions\ActionNotFoundException;
use LotGD\Core\Models\CharacterStats;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\Realm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CharacterController extends AbstractController
{
    /**
     * @param string $charId
     * @param string $action
     * @param GameService $gameService
     * @param Realm $realm
     * @param Security $security
     * @return Response
     */
    #[Route("/scene/{charId}/{action}", name: "scene_view", defaults: ["action"=>null])]
    public function sceneRender(
        $charId, $action,
        GameService $gameService,
        Realm $realm,
        Security $security
    ): Response {
        $user = $security->getUser(); /* @var $user \LotGD\Crate\WWW\Model\User */
        $character = $user->getCharacterWithId($charId);

        // Abort if the character does not belong to the user.
        if ($character === null) {
            throw new AccessDeniedHttpException("The given character does not belong to you or does not exist.");
        }

        // Start up game routine and set character.
        $game = $gameService->getGame();
        $game->setCharacter($character);

        // If an action has been given, take it.
        if (!empty($action)) {
            try {
                $game->takeAction($action);
                return $this->redirectToRoute("scene_view", ["charId" => $charId]);
            } catch (ActionNotFoundException $e) {
                $viewpoint_error = $e;
            }
        }

        $viewpoint = $game->getViewpoint();

        return $this->render('scene.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
            "character" => $game->getCharacter(),
            "character_stats" => new CharacterStats($game, $game->getCharacter()),
            "viewpoint" => $game->getViewpoint(),
            "viewpoint_error" => $viewpoint_error??null,
        ]);
    }
}
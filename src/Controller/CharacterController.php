<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Controller;

use LotGD\Core\Events\EventContextData;
use LotGD\Core\Exceptions\ActionNotFoundException;
use LotGD\Core\Exceptions\CharacterNotFoundException;
use LotGD\Core\Exceptions\InvalidConfigurationException;
use LotGD\Core\Exceptions\SceneNotFoundException;
use LotGD\Core\Models\CharacterStats;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\Realm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        string $charId,
        ?string $action,
        GameService $gameService,
        Realm $realm,
        Security $security,
        Request $request,
    ): Response {
        /* @var $user User */
        $user = $security->getUser();
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
                $game->takeAction($action, ["lotgd/crate-html" => ["request" => $request]]);
                return $this->redirectToRoute("scene_view", ["charId" => $charId]);
            } catch (ActionNotFoundException | SceneNotFoundException $e) {
                $viewpoint_error = $e;
            }
        }

        try {
            $viewpoint = $game->getViewpoint();
        } catch (CharacterNotFoundException $e) {
            $this->addFlash("warning", "Character was not found");
            return $this->redirectToRoute("ucp_root");
        } catch (InvalidConfigurationException $e) {
            $this->addFlash("error", "Invalid configuration: {$e->getMessage()}");
            return $this->redirectToRoute("ucp_root");
        }

        $attachments_compiled = [];

        foreach($viewpoint->getAttachments() as $attachment) {
            $contextData = EventContextData::create([
                "attachment" => $attachment,
                "renderedAttachment" => [],
            ]);

            $attachmentClass = get_class($attachment);

            $changedContextData = $game->getEventManager()->publish(
                "h/lotgd/crate/html/displayScene/renderAttachment/$attachmentClass",
                $contextData
            );

            if (empty($changedContextData->get("renderedAttachment"))) {
                $attachments_compiled[$attachment->getId()] = [
                    "type" => $attachmentClass,
                    "data" => $attachment->getData(),
                ];
            } else {
                $attachments_compiled[$attachment->getId()] = [
                    "type" => $attachmentClass,
                    "data" => $changedContextData->get("renderedAttachment"),
                ];
            }
        }

        return $this->render('scene.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
            "character" => $game->getCharacter(),
            "character_stats" => new CharacterStats($game, $game->getCharacter()),
            "viewpoint" => $game->getViewpoint(),
            "viewpoint_error" => $viewpoint_error??null,
            "attachments" => $attachments_compiled,
        ]);
    }
}
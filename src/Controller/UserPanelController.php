<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Controller;

use LotGD\Core\Models\Character;
use LotGD\Crate\WWW\Form\CharacterCreationType;
use LotGD\Crate\WWW\Service\Realm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use LotGD\Crate\WWW\Service\GameService;
use Symfony\Component\Security\Core\Security;

class UserPanelController extends AbstractController
{
    /**
     * @param GameService $gameService
     * @param Security $security
     * @return Response
     */
    #[Route("/ucp", name: "ucp_root")]
    public function root(
        GameService $gameService,
        Realm $realm,
        Security $security
    ): Response {
        return $this->render('ucp.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
        ]);
    }

    /**
     * @param GameService $gameService
     * @param Security $security
     * @return Response
     */
    #[Route("ucp/character/add", name: "ucp_character_add")]
    public function addCharacter(
        GameService $gameService,
        Realm $realm,
        Security $security,
        Request $request
    ): Response {
        $character = new Character();
        $form = $this->createForm(CharacterCreationType::class, $character);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $security->getUser()->addCharacter($character);

            $gameService->save();
            return $this->redirectToRoute("ucp_root");
        }

        return $this->render('ucp/character_add.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
            "form" => $form->createView(),
        ]);
    }
}
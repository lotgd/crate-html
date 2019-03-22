<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Controller;

use LotGD\Core\Models\Character;
use LotGD\Crate\WWW\Form\CharacterCreationType;
use LotGD\Crate\WWW\Service\Realm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use LotGD\Crate\WWW\Service\GameService;
use Symfony\Component\Security\Core\Security;

class UserPanelController extends AbstractController
{
    /**
     * @Route("/ucp", name="ucp_root")
     * @param GameService $gameService
     * @param Security $securitygit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function root(
        GameService $gameService,
        Realm $realm,
        Security $security
    ) {
        return $this->render('ucp.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
        ]);
    }

    /**
     * @Route("ucp/character/add", name="ucp_character_add")
     * @param GameService $gameService
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCharacter(
        GameService $gameService,
        Realm $realm,
        Security $security,
        Request $request
    ) {
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
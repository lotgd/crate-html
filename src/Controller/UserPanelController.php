<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Controller;

use LotGD\Core\Models\Character;
use LotGD\Crate\WWW\Form\CharacterCreationType;
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
    public function root(GameService $gameService, Security $security)
    {
        return $this->render('ucp.html.twig', [
            "realm" => [
                "name" => "Daenerys",
                "version" => "0.1-beta"
            ],
            "user" => $security->getUser(),
        ]);
    }

    /**
     * @Route("ucp/character/add", name="ucp_character_add")
     * @param GameService $gameService
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCharacter(GameService $gameService, Security $security, Request $request)
    {
        $character = new Character();
        $form = $this->createForm(CharacterCreationType::class, $character);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $security->getUser()->addCharacter($character);

            $gameService->save();
            return $this->redirectToRoute("ucp_root");
        }

        return $this->render('ucp/character_add.html.twig', [
            "realm" => [
                "name" => "Daenerys",
                "version" => "0.1-beta"
            ],
            "user" => $security->getUser(),
            "form" => $form->createView(),
        ]);
    }
}
<?php

namespace LotGD\Crate\WWW\Controller;


use LotGD\Crate\WWW\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class RootController extends AbstractController
{
    /**
     * @Route("/", name="root")
     */
    public function root(GameService $gameService, AuthenticationUtils $authenticationUtils, Security $security)
    {
        $user = $security->getUser();

        if ($user) {
            return $this->redirectToRoute("ucp_root");
        }

        return $this->render('offline.html.twig', [
            "realm" => [
                "name" => "Daenerys",
                "version" => "0.1-beta"
            ],
            "login" => [
                'last_username' => $authenticationUtils->getLastUsername(),
                "error" => $authenticationUtils->getLastAuthenticationError(),
            ],
        ]);
    }
}
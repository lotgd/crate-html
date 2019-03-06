<?php

namespace LotGD\Crate\WWW\Controller;


use LotGD\Crate\WWW\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class RootController extends AbstractController
{
    /**
     * @Route("/", name="root")
     */
    public function root(GameService $gameService, AuthenticationUtils $authenticationUtils)
    {

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
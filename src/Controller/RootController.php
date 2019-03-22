<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Controller;


use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\Realm;
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
    public function root(
        GameService $gameService,
        Realm $realm,
        AuthenticationUtils $authenticationUtils,
        Security $security
    ) {
        $user = $security->getUser();

        if ($user) {
            return $this->redirectToRoute("ucp_root");
        }

        return $this->render('offline.html.twig', [
            "realm" => $realm,
            "login" => [
                'last_username' => $authenticationUtils->getLastUsername(),
                "error" => $authenticationUtils->getLastAuthenticationError(),
            ],
        ]);
    }
}
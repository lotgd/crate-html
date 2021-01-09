<?php
declare(strict_types=1);

namespace LotGD\Crate\WWW\Controller;

use LotGD\Crate\WWW\Form\UserCreationType;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\Realm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RootController extends AbstractController
{
    #[Route("/", name: "root")]
    public function root(
        GameService $gameService,
        Realm $realm,
        AuthenticationUtils $authenticationUtils,
        Security $security
    ): Response {
        return $this->redirectIfSignedIn($security) ?? $this->render('offline.html.twig', [
            "realm" => $realm,
            "login" => [
                'last_username' => $authenticationUtils->getLastUsername(),
                "error" => $authenticationUtils->getLastAuthenticationError(),
            ],
        ]);
    }

    #[Route("/register", name: "registration")]
    public function registration(
        GameService $gameService,
        Realm $realm,
        Request $request,
        AuthenticationUtils $authenticationUtils,
        Security $security
    ): Response {
        $response = $this->redirectIfSignedIn($security);
        if ($response) {
            return $response;
        }

        $user = new User();
        $form = $this->createForm(UserCreationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $gameService->getEntityManager()->persist($user);
            $gameService->getEntityManager()->flush();

            return $this->render('offline.html.twig', [
                "realm" => $realm,
                "login" => [
                    'last_username' => $user->getUsername(),
                    "error" => $authenticationUtils->getLastAuthenticationError(),
                ],
            ]);
        }

        return $this->render('offline/registration.html.twig', [
            "realm" => $realm,
            "form" => $form->createView(),
        ]);
    }

    protected function redirectIfSignedIn(Security $security): ?Response
    {
        $user = $security->getUser();

        if ($user) {
            return $this->redirectToRoute("ucp_root");
        }

        return null;
    }
}
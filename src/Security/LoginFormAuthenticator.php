<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Security;


use Doctrine\Common\Util\Debug;
use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $gameService;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $userService;

    public function __construct(
        GameService $gameService,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserService $userService,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->gameService = $gameService;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userService = $userService;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        if ($request->attributes->get("_route") === "root" and $request->isMethod("POST")) {
            return true;
        } else {
            return false;
        }
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get("email"),
            'password' => $request->request->get("password"),
            "csrf_token" => $request->request->get("_csrf_token"),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials["email"]
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken("authenticate", $credentials["csrf_token"]);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->userService->getUserByEmail($credentials["email"]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException("Account could not be found.");
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $user->passwordIsValid($credentials["password"]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate("ucp_root"));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate("root");
    }
}
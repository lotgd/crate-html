<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Security;


use LotGD\Crate\WWW\Model\User;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\UserService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function loadUserByUsername($username): User
    {
        $user = $this->userService->getUserByEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException("The account with the given mail address {$username} has not been found.");
        }

        return $user;
    }

    public function supportsClass($class): bool
    {
        return ($class === User::class);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf("This provider does not support class %s", get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }
}
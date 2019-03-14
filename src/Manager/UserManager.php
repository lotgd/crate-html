<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Manager;


use LotGD\Core\Game;
use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getUserByEmail($email): ?UserInterface
    {
        return $this->game
            ->getEntityManager()
            ->getRepository(User::class)
            ->findOneBy(["email" => $email]);
    }

    public function isPasswordValid(UserInterface $user, string $password)
    {
        $passwordHash = $user->getPassword();
        $verified = password_verify($password, $passwordHash);

        if (password_needs_rehash($passwordHash, PASSWORD_DEFAULT)) {
            $user->setPassword($password);
        }
    }

    public function createNewWithPassword($name, $email, $password): User
    {
        $user = new User($name, $email, $password);
        $user->save($this->game->getEntityManager());
        return $user;
    }
}
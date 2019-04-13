<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Controller;


use LotGD\Crate\WWW\AdministrationToolboxes\CharacterToolbox;
use LotGD\Crate\WWW\AdministrationToolboxes\UserToolbox;
use LotGD\Crate\WWW\Service\GameService;
use LotGD\Crate\WWW\Service\Realm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdministrationController extends AbstractController
{
    /**
     * @param GameService $gameService
     * @param Realm $realm
     * @param Security $security
     * @return Response
     * @Route("/admin", name="admin")
     */
    public function root(
        GameService $gameService,
        Realm $realm,
        Security $security
    ): Response {
        return $this->render('admin.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
            "toolbox" => null,
        ]);
    }

    /**
     * @param GameService $gameService
     * @param Realm $realm
     * @param Security $security
     * @return Response
     * @Route("/admin/toolbox/{type}/{action}/{id}", name="admin_toolbox", defaults={"action":null, "id":null})
     */
    public function toolbox(
        string $type,
        ?string $action,
        ?string $id,
        GameService $gameService,
        Realm $realm,
        Security $security,
        Request $request
    ): Response {
        if ($type === "user") {
            $toolboxClass = UserToolbox::class;
        } elseif ($type === "character") {
            $toolboxClass = CharacterToolbox::class;
        } else {
            throw new NotFoundHttpException("This administration toolbox tool was not found.");
        }

        $toolbox = (new $toolboxClass($action, $id, $this, $gameService->getGame(), $security->getUser(), $request))->getToolbox();

        return $this->render('admin.html.twig', [
            "realm" => $realm,
            "user" => $security->getUser(),
            "toolbox" => $toolbox,
        ]);
    }

    public function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return parent::createForm($type, $data, $options);
    }

    public function addFlash(string $type, string $message)
    {
        parent::addFlash($type, $message);
    }
}
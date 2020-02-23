<?php declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 23/02/2020
 * Time: 00:24
 */

namespace App\Controller;

use App\Form\Type\UserType;
use App\Contracts\GitHubServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @var GitHubServiceInterface
     */
    private $gitHubService;

    public function __construct(GitHubServiceInterface $gitHubService)
    {
        $this->gitHubService = $gitHubService;
    }

    /**
     * @Route("/", name="user_popular_language", methods="GET")
     */
    public function getPopularLanguage(Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request, null, [
            'action' => $this->generateUrl('user_popular_language'),
        ]);

        $favouriteLanguage  = null;
        $errorMsg           = null;
        $username           = null;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data       = $form->getData();
                $username   = $data['username'];

                $favouriteLanguage = $this->gitHubService->getUserPopularLanguage($username);
            } catch (\Exception $ex) {
                $errorMsg = $ex->getMessage();
            }
        }

        return $this->render('user/index.html.twig', [
            'form'                  => $form->createView(),
            'favourite_language'    => $favouriteLanguage,
            'error_msg'             => $errorMsg,
            'username'              => $username,
        ]);
    }
}

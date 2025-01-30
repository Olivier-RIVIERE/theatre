<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {  $valideevenements = $evenementRepository->findValidEvent(true);
        // dd($valideevenements);
        $datapaginator = $paginator->paginate(
            $valideevenements,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('home/index.html.twig', [
            'eventsvalides' => $datapaginator
        ]);
    }
    #[Route("/profil", name:"profil")]
    public function profil(){
        $user = $this->getUser();
        return $this->render("home/profil.html.twig", [
            "utilisateur" => $user
        ]);
    }

    #[Route('/paypal', name: 'paypal')]
    public function paypal(){
        return $this->render('reservation/paypal.html.twig');
    }
}

<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Repository\EvenementRepository;
use App\Repository\PaiementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $valideevenements = $evenementRepository->findValidEvent(true);
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
    #[Route("/profil", name: "profil")]
    public function profil()
    {
        $utilisateur = $this->getUser();
        return $this->render("home/profil.html.twig", [
            "utilisateur" => $utilisateur
        ]);
    }

    #[Route('/eventbyuser', name: 'eventbyuser')]
    public function eventsByUser(EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();  // Récupère l'utilisateur connecté
        $evenements = $entityManager->getRepository(Evenement::class)
            ->findBy(['user' => $utilisateur]);

        return $this->render('home/eventbyuser.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/create-checkout-session/{id}', name: 'create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(int $id, EvenementRepository $evenementRepository, PaiementRepository $paiementRepository, Request $request): JsonResponse
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $evenement = $evenementRepository->find($id);
        if (!$evenement) {
            return new JsonResponse(['error' => 'Événement introuvable'], 404);
        }

        $content = json_decode($request->getContent(), true);
        $quantity = $content['quantity'] ?? 1;

        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non connecté'], 400);
        }

        $successUrl = $this->generateUrl('payment_success', ['eventId' => $evenement->getId()], 0);
        $cancelUrl = $this->generateUrl('payment_cancel', ['eventId' => $evenement->getId()], 0);


        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $evenement->getTitre(),
                        ],
                        'unit_amount' => $evenement->getPrix() * 100,
                    ],
                    'quantity' => $quantity,
                ]],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            $paiement = new Paiement();
            $paiement->setUtilisateur($user);
            $paiement->setEvenement($evenement);
            $paiement->setMontant($evenement->getPrix() * $quantity);
            $paiement->setStatut('en attente');
            $paiement->setStripeSessionId($session->id);
            $paiement->setDatePaiement(new \DateTime());

            $paiementRepository->save($paiement);

            return new JsonResponse(['id' => $session->id]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/payment-success', name: 'payment_success')]
    public function paymentSuccess(Request $request, EvenementRepository $evenementRepository): Response
    {
        $eventId = $request->query->get('eventId');
        $evenement = $evenementRepository->find($eventId);

        return $this->render('reservation/success.html.twig', [
            'evenement' => $evenement,
        ]);
    }


    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function paymentCancel(Request $request, EvenementRepository $evenementRepository): Response
    {
        $eventId = $request->query->get('eventId');
        $evenement = $evenementRepository->find($eventId);

        return $this->render('reservation/cancel.html.twig', [
            'evenement' => $evenement,
        ]);
    }
}

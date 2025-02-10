<?php

namespace App\Controller;

use App\Api\CitationApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CitationController extends AbstractController
{
    private CitationApi $citationApi;

    public function __construct(CitationApi $citationApi)
    {
        $this->citationApi = $citationApi;
    }

    #[Route('/', name: 'app_citation')]
    public function index(): Response
    {
        try {
            $citation = $this->citationApi->getRandomCitation();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Impossible de récupérer une citation pour le moment.');
            return $this->redirectToRoute('app_citation'); // Recharge la page en cas d'erreur
        }

        return $this->render('citation/index.html.twig', [
            'citation' => $citation,
        ]);
    }

    #[Route('/random', name: 'random_citation')]
    public function random(): Response
    {
        return $this->redirectToRoute('app_citation'); // Recharge la page pour afficher une nouvelle citation
    }
}

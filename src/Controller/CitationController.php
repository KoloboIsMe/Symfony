<?php

namespace App\Controller;

use App\Api\CitationApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CitationController extends AbstractController
{
    private CitationApi $citationApi;

    public function __construct(CitationApi $citationApi)
    {
        $this->citationApi = $citationApi;
    }

    #[Route('/explain', name: 'explain_citation', methods: ['POST'])]
    public function explain(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['citation'], $data['author'], $data['book'])) {
            return new JsonResponse(['error' => 'Données invalides'], 400);
        }

        // TODO : Appel à une IA pour générer l'explication.
        $explanation = sprintf(
            "Cette citation de <strong>%s</strong> dans le livre <em>%s</em> illustre un thème important sur la vie et la réflexion personnelle.",
            htmlspecialchars($data['author']),
            htmlspecialchars($data['book'])
        );

        return new JsonResponse(['explanation' => $explanation]);
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

    #[Route('/random-citation', name: 'random_citation')]
    public function getRandomCitation(): JsonResponse
    {
        // Appel API pour récupérer une citation aléatoire
        $citation = $this->citationApi->getRandomCitation();

        // Renvoi de la citation sous forme JSON
        return new JsonResponse([
            'text' => $citation->getText(),
            'author' => $citation->getAuthor()->getName(),
            'book' => $citation->getBook()->getTitle()
        ]);
    }
}

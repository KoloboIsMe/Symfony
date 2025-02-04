<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Repository\CitationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CitationController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}
    #[Route('/', name: 'app_citation')]
    public function index(): Response
    {
        /**
         * @var CitationRepository $citationRepository
         */
        $citationRepository = $this->doctrine->getRepository(Citation::class);
        $randomCitation = $citationRepository->getRandomCitation();
        return $this->render('citation/index.html.twig', [
            'citation' => $randomCitation,
        ]);
    }
}

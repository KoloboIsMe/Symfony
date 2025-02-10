<?php

namespace App\Controller;

use App\Api\CitationApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CitationController extends AbstractController
{
    private CitationApi $citationApi;
    public function __construct(){
        $this->citationApi = new CitationApi(HttpClient::create(),$_ENV['API_BASE_URL']);
    }
    #[Route('/', name: 'app_citation')]
    public function index(): Response
    {
        return $this->render('citation/index.html.twig', [
            'citation' => $this->citationApi->getRandomCitation(),
        ]);
    }
}

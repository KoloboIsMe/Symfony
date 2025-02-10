<?php

namespace App\Api;

use App\Api\Entity\Author;
use App\Api\Entity\Book;
use App\Api\Entity\Citation;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CitationApi
{
    private HttpClientInterface $httpClient;
    private string $baseUrl;
    /**
     * @var array Un mapping d'auteurs et livres pour optimiser les requêtes
     */
    private array $mapping;

    public function __construct(HttpClientInterface $httpClient, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
        $this->mapping = [];
    }

    /**
     * @return Citation[]
     */
    public function getCitations(): array
    {
        $url = $this->baseUrl . '/api/citations';

        try {
            $response = $this->httpClient->request(
                'GET',
                $url,
                [
                'headers' => [
                    'Accept' => 'application/ld+json',
                ],
            ]);

            $response = $response->toArray();
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface $e) {
            $response = [];
        }

        $citations = [];
        foreach ($response['member'] as $citation){
            $citations[] = new Citation(
                $citation['id'],
                $citation['text'],
                $this->getAuthor($citation['author']),
                $this->getBook($citation['book'])
            );
        }
        return $citations;
    }
    public function getRandomCitation(): Citation {
        $citations = $this->getCitations();
        shuffle($citations);
        return array_shift($citations);
    }

    public function getAuthor(string $index): ?Author
    {
        if(!str_starts_with($index,'/api')){
            $index = '/api/authors/' . $index;
        }

        if(in_array($index, $this->mapping)){
            return $this->mapping[$index];
        }

        $url = $this->baseUrl . $index;

        try {
            $response = $this->httpClient->request(
                'GET',
                $url,
                [
                    'headers' => [
                        'Accept' => 'application/ld+json',
                    ],
                ]);

            $response = $response->toArray();
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface $e) {
            return null;
        }

        $author = new Author($response['id'], $response['name']);
        $this->mapping[$index] = $author;
        return $author;
    }

    public function getBook(string $index): ?Book
    {
        if(!str_starts_with($index,'/api')){
            $index = '/api/books/' . $index;
        }

        if(in_array($index, $this->mapping)){
            return $this->mapping[$index];
        }

        $url = $this->baseUrl . $index;

        try {
            $response = $this->httpClient->request(
                'GET',
                $url,
                [
                    'headers' => [
                        'Accept' => 'application/ld+json',
                    ],
                ]);

            $response = $response->toArray();
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface $e) {
            return null;
        }

        $book = new Book($response['id'], $response['title']);
        $this->mapping[$index] = $book;
        return $book;
    }
}

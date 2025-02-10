<?php

namespace App\Tests\Integration;

use App\Api\CitationApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CitationApiTest extends KernelTestCase
{
    private CitationApi $citationApi;

    protected function setUp(): void
    {
        self::bootKernel(); // Démarre le noyau Symfony

        $httpClient = static::getContainer()->get(HttpClientInterface::class);

        $this->citationApi = new CitationApi($httpClient, $_ENV['API_BASE_URL']);
    }

    public function testGetCitationsReturnsValidResponse()
    {
        $citations = $this->citationApi->getCitations();

        $this->assertIsArray($citations);
        $this->assertNotEmpty($citations, "La liste des citations est vide");

        $citation = $citations[0];

        $this->assertNotNull($citation->getId());
        $this->assertNotNull($citation->getText());
        $this->assertNotNull($citation->getAuthor());
        $this->assertNotNull($citation->getBook());
    }

    public function testGetRandomCitationReturnsACitation()
    {
        $citation = $this->citationApi->getRandomCitation();

        $this->assertNotNull($citation);
        $this->assertNotNull($citation->getId());
        $this->assertNotNull($citation->getText());
    }

    public function testGetAuthorReturnsAuthor()
    {
        $author = $this->citationApi->getAuthor('/api/authors/1');

        $this->assertNotNull($author);
        $this->assertNotNull($author->getId());
        $this->assertNotNull($author->getName());
    }

    public function testGetBookReturnsBook()
    {
        $book = $this->citationApi->getBook('/api/books/1');

        $this->assertNotNull($book);
        $this->assertNotNull($book->getId());
        $this->assertNotNull($book->getTitle());
    }
}

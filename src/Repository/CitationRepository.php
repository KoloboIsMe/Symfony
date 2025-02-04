<?php

namespace App\Repository;

use App\Entity\Citation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Citation>
 */
class CitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citation::class);
    }
    /**
     * @return Citation|null Returns a random Citation object
     */
    public function getRandomCitation(): ?Citation
    {
        $count = $this->count();
        if($count === 0) {
            return null;
        }
        $offset = rand(0, $count - 1);
        return $this->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

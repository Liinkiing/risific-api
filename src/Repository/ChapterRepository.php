<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Chapter::class);
    }


    public function findByRisificSlugAndPosition(string $slug, int $position): ?Chapter
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->innerJoin('c.risific', 'risific')
            ->andWhere($qb->expr()->eq('risific.slug', ':slug'))
            ->setParameter('slug', $slug)
            ->andWhere($qb->expr()->eq('c.position', $position))
            ->getQuery()
            ->getOneOrNullResult();
    }

}

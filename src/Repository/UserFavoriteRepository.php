<?php

namespace App\Repository;

use App\Entity\UserFavorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserFavorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFavorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFavorite[]    findAll()
 * @method UserFavorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFavoriteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFavorite::class);
    }

}

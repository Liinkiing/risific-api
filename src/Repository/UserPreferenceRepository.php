<?php

namespace App\Repository;

use App\Entity\UserPreference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPreference|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPreference|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPreference[]    findAll()
 * @method UserPreference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPreferenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPreference::class);
    }
}

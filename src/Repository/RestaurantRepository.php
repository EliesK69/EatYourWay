<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    /**
     * This method allow us to find public restaurants based on number of restaurants
     *
     * @param integer $nbRestaurants
     * @return array
     */
    public function findRestaurantsForUserOrPublic(?int $nbRestaurants = null, $user = null): array
{
    $qb = $this->createQueryBuilder('r');

    if ($user) {
        // Ajoute une condition pour chercher les restaurants de l'utilisateur et les restaurants publics
        $qb->where('r.user = :user OR r.isPublic = true')
           ->setParameter('user', $user);
    } else {
        // Si aucun utilisateur n'est spécifié, se limiter aux restaurants publics
        $qb->where('r.isPublic = true');
    }

    $qb->orderBy('r.createdAt', 'DESC');

    if (null !== $nbRestaurants) {
        $qb->setMaxResults($nbRestaurants);
    }

    return $qb->getQuery()->getResult();
}
}

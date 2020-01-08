<?php

namespace App\Repository;


use App\Entity\Building;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Building|null find($id, $lockMode = null, $lockVersion = null)
 * @method Building|null findOneBy(array $criteria, array $orderBy = null)
 * @method Building[]    findAll()
 * @method Building[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Building::class);
    }

    public function findAllQueryBuilder($filter = '')
    {
        $qb = $this->createQueryBuilder('building');
        if ($filter) {
            $qb->andWhere('building.city LIKE :filter OR building.street LIKE :filter')
                ->setParameter('filter', '%'.$filter.'%');
        }
        return $qb;
    }
}
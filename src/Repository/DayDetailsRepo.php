<?php

namespace App\Repository;

use App\Entity\DayDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DayDetails>
 *
 * @method DayDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method DayDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method DayDetails[]    findAll()
 * @method DayDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayDetailsRepo extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayDetails::class);
    }

    public function save(DayDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DayDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}

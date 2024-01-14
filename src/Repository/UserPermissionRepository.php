<?php

namespace App\Repository;

use App\Entity\UserPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPermission>
 *
 * @method UserPermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPermission[]    findAll()
 * @method UserPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPermission::class);
    }

    public function removeByUserId(int $userId): void
    {
        $this->createQueryBuilder('a')
            ->delete()
            ->where('a.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }

    public function save(UserPermission $userPermission): void
    {
        $this->getEntityManager()->persist($userPermission);
        $this->getEntityManager()->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\Form;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Form>
 *
 * @method Form|null find($id, $lockMode = null, $lockVersion = null)
 * @method Form|null findOneBy(array $criteria, array $orderBy = null)
 * @method Form[]    findAll()
 * @method Form[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Form::class);
    }

   public function findByIds(array $ids): array
   {
       return $this->createQueryBuilder('f')
           ->andWhere('f.id IN (:ids)')
           ->setParameter('ids', $ids)
           ->getQuery()
           ->getResult()
       ;
   }

    public function countActive(): int
    {
         return $this->createQueryBuilder('f')
              ->select('COUNT(f.id)')
              ->andWhere('f.deleted_at IS NULL')
              ->getQuery()
              ->getSingleScalarResult()
         ;
    }

    public function countArchived(): int
    {
         return $this->createQueryBuilder('f')
              ->select('COUNT(f.id)')
              ->andWhere('f.deleted_at IS NOT NULL')
              ->getQuery()
              ->getSingleScalarResult()
         ;
    }

    public function findByIdsAndStatus(?array $ids = null, bool $isArchived = false): array
    {
        $builder = $this->createQueryBuilder('f')
            ->andWhere('f.deleted_at IS ' . ($isArchived ? 'NOT NULL' : 'NULL'));

        if ($ids !== null) {
            $builder->andWhere('f.id IN (:ids)')
                ->setParameter('ids', $ids);
        }

        return $builder->getQuery()
            ->getResult();
    }

    public function save(Form $form): void
    {
        $this->_em->persist($form);
        $this->_em->flush();
    }

    public function archive(Form $form): void
    {
        $form->setDeletedAt(new \DateTimeImmutable());
        $this->_em->persist($form);
        $this->_em->flush();
    }

    public function recover(Form $form): void
    {
        $form->setDeletedAt(null);
        $this->_em->persist($form);
        $this->_em->flush();
    }
}

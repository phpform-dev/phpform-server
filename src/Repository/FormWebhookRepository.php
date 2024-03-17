<?php

namespace App\Repository;

use App\Entity\FormWebhook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormWebhook|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormWebhook|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormWebhook[]    findAll()
 * @method FormWebhook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormWebhookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormWebhook::class);
    }

    // Add custom repository methods here
    public function findByFormId(int $formId): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.form = :formId')
            ->setParameter('formId', $formId)
            ->getQuery()
            ->getResult();
    }

    public function countByFormId(int $formId): int
    {
        return $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->andWhere('w.form = :formId')
            ->setParameter('formId', $formId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(FormWebhook $formWebhook): void
    {
        $this->_em->persist($formWebhook);
        $this->_em->flush();
    }

    public function delete(FormWebhook $formWebhook): void
    {
        $this->_em->remove($formWebhook);
        $this->_em->flush();
    }
}

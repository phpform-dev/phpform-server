<?php

namespace App\Repository;

use App\Entity\FormField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormField>
 *
 * @method FormField|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormField|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormField[]    findAll()
 * @method FormField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormField::class);
    }

    public function save(FormField $formField): void
    {
        $this->_em->persist($formField);
        $this->_em->flush();
    }

    public function deleteById(int $id): void
    {
        $this->createQueryBuilder('a')
            ->delete()
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function moveById(int $formFieldId, string $direction): void
    {
        if (!in_array($direction, ['up', 'down'])) {
            throw new \InvalidArgumentException('Invalid direction');
        }

        $formField = $this->find($formFieldId);
        if ($formField === null) {
            throw new \InvalidArgumentException('Invalid form field');
        }

        $position = $formField->getPosition();
        $otherPosition = $direction === 'up' ? $position - 1 : $position + 1;

        $otherFormField = $this->findOneBy([
            'form' => $formField->getForm(),
            'position' => $otherPosition,
        ]);

        if ($otherFormField === null) {
            throw new \InvalidArgumentException('Invalid direction');
        }

        $formField->setPosition($otherPosition);
        $otherFormField->setPosition($position);

        $this->_em->persist($formField);
        $this->_em->persist($otherFormField);
        $this->_em->flush();
    }

    public function getAllByFormId(int $formId): array
    {
        return $this->findBy([
            'form' => $formId,
        ], [
            'position' => 'ASC',
        ]);
    }

    public function countByFormId(int $formId): int
    {
        $qb = $this->createQueryBuilder('ff');
        $qb->select('COUNT(ff.id)')
            ->andWhere('ff.form = :formId')
            ->setParameter('formId', $formId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}

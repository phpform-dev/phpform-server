<?php

namespace App\Repository;

use App\Entity\Submission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Submission>
 *
 * @method Submission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Submission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Submission[]    findAll()
 * @method Submission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubmissionRepository extends ServiceEntityRepository
{
    const SUBMISSION_STATUS_NEW = 'new';
    const SUBMISSION_STATUS_FLAGGED = 'flagged';
    const SUBMISSION_STATUS_DELETED = 'deleted';

    public function __construct(
        private readonly FormRepository $formRepository,
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Submission::class);
    }

    public function create(int $formId, array $data): ?Submission
    {
        $form = $this->formRepository->find($formId);

        $submission = new Submission();
        $submission->setForm($form);
        $submission->setAnswers($data);
        $submission->setCreatedAt(new \DateTimeImmutable());

        $this->_em->persist($submission);
        $this->_em->flush();

        return $submission;
    }

    public function findByFormIdWithPagination(int $formId, int $page = 1, int $perPage = 10): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.form = :formId')
            ->andWhere('s.deleted_at IS NULL')
            ->setParameter('formId', $formId)
            ->orderBy('s.created_at', 'DESC')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    public function findNewByFormIdWithPagination(int $formId, int $page = 1, int $perPage = 10): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.form = :formId')
            ->andWhere('s.deleted_at IS NULL')
            ->andWhere('s.read_at IS NULL')
            ->setParameter('formId', $formId)
            ->orderBy('s.created_at', 'DESC')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    public function findFlaggedByFormIdWithPagination(int $formId, int $page = 1, int $perPage = 10): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.form = :formId')
            ->andWhere('s.deleted_at IS NULL')
            ->andWhere('s.flagged_at IS NOT NULL')
            ->setParameter('formId', $formId)
            ->orderBy('s.created_at', 'DESC')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    public function findDeletedByFormIdWithPagination(int $formId, int $page = 1, int $perPage = 10): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.form = :formId')
            ->andWhere('s.deleted_at IS NOT NULL')
            ->setParameter('formId', $formId)
            ->orderBy('s.created_at', 'DESC')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    public function getCountByStatus(string $status, array $formIds): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('IDENTITY(s.form) as formId, COUNT(s.id) as cnt')
            ->andWhere('s.form IN (:formIds)');

        switch ($status) {
            case self::SUBMISSION_STATUS_NEW:
                $qb->andWhere('s.read_at IS NULL');
                break;
            case self::SUBMISSION_STATUS_FLAGGED:
                $qb->andWhere('s.flagged_at IS NOT NULL');
                break;
            case self::SUBMISSION_STATUS_DELETED:
                $qb->andWhere('s.deleted_at IS NOT NULL');
                break;
            default:
                $qb->andWhere('s.deleted_at IS NULL');
                break;
        }

        $qb->setParameter('formIds', $formIds)
            ->groupBy('s.form');

        $results = $qb->getQuery()->getResult();

        $counts = [];
        foreach ($results as $result) {
            $counts[$result['formId']] = $result['cnt'];
        }

        return $counts;
    }

    public function delete(int $id): void
    {
        $submission = $this->find($id);
        $submission->setDeletedAt(new \DateTimeImmutable());
        $submission->setFlaggedAt(null);
        $submission->setReadAt(new \DateTimeImmutable());

        $this->_em->persist($submission);
        $this->_em->flush();
    }

    public function undelete(int $id): void
    {
        $submission = $this->find($id);
        $submission->setDeletedAt(null);

        $this->_em->persist($submission);
        $this->_em->flush();
    }

    public function flag(int $id): void
    {
        $submission = $this->find($id);
        $submission->setFlaggedAt(new \DateTimeImmutable());
        $submission->setReadAt(new \DateTimeImmutable());

        $this->_em->persist($submission);
        $this->_em->flush();
    }

    public function unflag(int $id): void
    {
        $submission = $this->find($id);
        $submission->setFlaggedAt(null);

        $this->_em->persist($submission);
        $this->_em->flush();
    }

    public function read(int $id): void
    {
        $submission = $this->find($id);
        $submission->setReadAt(new \DateTimeImmutable());

        $this->_em->persist($submission);
        $this->_em->flush();
    }

    public function unread(int $id): void
    {
        $submission = $this->find($id);
        $submission->setReadAt(null);

        $this->_em->persist($submission);
        $this->_em->flush();
    }
}

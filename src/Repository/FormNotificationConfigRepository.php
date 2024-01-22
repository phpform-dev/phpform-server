<?php

namespace App\Repository;

use App\Entity\FormNotificationConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormNotificationConfig>
 *
 * @method FormNotificationConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormNotificationConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormNotificationConfig[]    findAll()
 * @method FormNotificationConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormNotificationConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormNotificationConfig::class);
    }

    public function save(FormNotificationConfig $formNotificationConfig): FormNotificationConfig
    {
        $this->_em->persist($formNotificationConfig);
        $this->_em->flush();

        return $formNotificationConfig;
    }
}

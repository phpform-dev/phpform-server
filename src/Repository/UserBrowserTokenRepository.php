<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserBrowserToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBrowserToken>
 *
 * @method UserBrowserToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBrowserToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBrowserToken[]    findAll()
 * @method UserBrowserToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBrowserTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBrowserToken::class);
    }

    public function createForUser(User $user, string $endpoint, string $publicKey, string $authToken): UserBrowserToken
    {
        $userBrowserToken = new UserBrowserToken();
        $userBrowserToken->setUser($user);
        $userBrowserToken->setEndpoint($endpoint);
        $userBrowserToken->setPublicKey($publicKey);
        $userBrowserToken->setAuthToken($authToken);
        $userBrowserToken->setCreatedAt(new \DateTimeImmutable());
        $userBrowserToken->setUpdatedAt(new \DateTimeImmutable());
        $this->getEntityManager()->persist($userBrowserToken);
        $this->getEntityManager()->flush();

        return $userBrowserToken;
    }

    public function deleteByEndpoint(string $endpoint): void
    {
        $this->createQueryBuilder('t')
            ->delete()
            ->where('t.endpoint = :endpoint')
            ->setParameter('endpoint', $endpoint)
            ->getQuery()
            ->execute();
    }
}

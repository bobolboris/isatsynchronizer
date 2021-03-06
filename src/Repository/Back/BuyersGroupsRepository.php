<?php

namespace App\Repository\Back;

use App\Entity\Back\BuyersGroups;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method BuyersGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyersGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyersGroups[]    findAll()
 * @method BuyersGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method BuyersGroups[]    findByIds(string $ids)
 * @method void    persist(BuyersGroups $instance)
 * @method void    persistAndFlush(BuyersGroups $instance)
 * @method void    remove(BuyersGroups $instance)
 * @method void    removeAndFlush(BuyersGroups $instance)
 */
class BuyersGroupsRepository extends BackRepository
{
    /**
     * BuyersGroupsRepository constructor.
     * @param LoggerInterface $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($logger, $registry, BuyersGroups::class);
    }
}

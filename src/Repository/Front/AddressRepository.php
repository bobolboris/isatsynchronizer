<?php

namespace App\Repository\Front;

use App\Entity\Front\Address;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Address[]    findByIds(string $ids)
 * @method void    persist(Address $instance)
 * @method void    persistAndFlush(Address $instance)
 * @method void    remove(Address $instance)
 * @method void    removeAndFlush(Address $instance)
 */
class AddressRepository extends FrontRepository
{
    /**
     * AddressRepository constructor.
     * @param LoggerInterface $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($logger, $registry, Address::class);
    }
}

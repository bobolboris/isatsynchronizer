<?php

namespace App\Repository\Front;

use App\Entity\Front\CustomerApproval;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method CustomerApproval|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerApproval|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerApproval[]    findAll()
 * @method CustomerApproval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method CustomerApproval[]    findByIds(string $ids)
 * @method void    persist(CustomerApproval $instance)
 * @method void    persistAndFlush(CustomerApproval $instance)
 * @method void    remove(CustomerApproval $instance)
 * @method void    removeAndFlush(CustomerApproval $instance)
 */
class CustomerApprovalRepository extends FrontRepository
{
    /**
     * CustomerApprovalRepository constructor.
     * @param LoggerInterface $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($logger, $registry, CustomerApproval::class);
    }
}

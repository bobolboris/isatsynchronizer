<?php

namespace App\Repository\Back;

use App\Entity\Back\Currency;
use App\Helper\ExceptionFormatter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Currency[]    findByIds(string $ids)
 * @method void    persist(Currency $instance)
 * @method void    persistAndFlush(Currency $instance)
 * @method void    remove(Currency $instance)
 * @method void    removeAndFlush(Currency $instance)
 */
class CurrencyRepository extends BackRepository
{
    /**
     * CurrencyRepository constructor.
     * @param LoggerInterface $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($logger, $registry, Currency::class);
    }

    /**
     * @param string $name
     * @param int $shopId
     * @return Currency|null
     */
    public function findOneByNameAndShopId(string $name, int $shopId): ?Currency
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.name = :name')
                ->setParameter('name', $name)
                ->andWhere('c.shopId = :shopId')
                ->setParameter('shopId', $shopId)
                ->orderBy('c.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $this->logger->error(ExceptionFormatter::f($e->getMessage()));

            return null;
        }
    }

    /**
     * @return array
     */
    public function getCurrentCourse(): array
    {
        $connection = $this->getEntityManager()->getConnection();
        /* @noinspection SqlNoDataSourceInspection */
        $sql = "
            SELECT 
                `one`.value as 'грн', `two`.value as 'р', 1 as '$' 
            FROM
                (
                    SELECT 
                      * 
                    FROM `SS_currencies` 
                    WHERE 
                        `shop_id` = 0 
                        AND `name` = 'грн' 
                    ORDER BY `id` DESC LIMIT 1) as `one` 
                    INNER JOIN
                        (
                            SELECT 
                                * 
                            FROM 
                                `SS_currencies` 
                            WHERE `shop_id` = 0 
                                  AND `name` = 'р' 
                            ORDER BY `id` DESC LIMIT 1
              ) 
              as `two` ON 1 = 1";
        $result = $connection->fetchAll($sql);

        return $result[0];
    }
}

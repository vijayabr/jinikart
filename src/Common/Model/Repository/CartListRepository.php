<?php

namespace Common\Model\Repository;

/**
 * CartListRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartListRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllData(){
        $query = $this->createQueryBuilder('cl')
        ->select('cl');
        $query = $query->getQuery()->useQueryCache(true);
        return $query->getResult();
    }
}

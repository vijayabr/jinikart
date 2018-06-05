<?php

namespace Common\Model\Repository;

/**
 * Product_DescriptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Product_DescriptionRepository extends \Doctrine\ORM\EntityRepository
{
    public function productdescIdBasedOnRamsize($ramsize) {

        $products = $this->getEntityManager()
        ->createQuery(
            'SELECT d.id FROM Model:Product_Description d WHERE d.ramSize <= :ramsize')
            ->setParameter('ramsize', $ramsize)
             ->getResult();

            return $products;
            
            
    }
    
    public function productdescIdBasedOnCamera($camera) {
        $products = $this->getEntityManager()
        ->createQuery(
            'SELECT d.id FROM Model:Product_Description d WHERE d.camera <= :camera')
            ->setParameter('camera', $camera)
            ->getResult();
            return $products;
            
    }
           
    
}

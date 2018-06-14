<?php

namespace Common\Model\Repository;

use Doctrine\ORM\QueryBuilder;
use Common\Model\Product;
use Common\Model\Product_Photo;
use Common\Model\Product_Detail_List;
use Common\Model\Merchant;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{

    public function productsearchBasedonBrand($brand,$min,$max) {
            $products = $this->getEntityManager()
          ->createQuery(
            'SELECT p FROM Model:Product p WHERE p.brandId =:brand AND p.productPrice >=:min AND p.productPrice <= :max ORDER BY p.productName ASC')
            ->setParameter('brand', $brand)
            ->setParameter('min', $min)
            ->setParameter('max', $max)
           ->getResult();
            return $products;
       
            
    }
    
    public function productsearch() {
        $products = $this->getEntityManager()
        ->createQuery(
            'SELECT p FROM Model:Product p ORDER BY p.productName ASC')
            ->getResult();
            return $products;
    }
    
   
    public function completeproductinfo($pName) {

        $productinfo = $this->getEntityManager()
        ->createQuery('SELECT p FROM Model:Product p JOIN p.brandId brand b WHERE p.productName =:pName')
         ->setParameter('pName', $pName)
         ->getResult();
          return $productinfo;
    }
    
    //Used in listing (Merchant)
    public function findAllProductDetails($id)
    {
          $em = $this->getEntityManager();
          // dump($id);die;
            $qb = $em->createQueryBuilder();
            $qb->select('l')
            ->from('Common\Model\Product_Detail_List','l')
            ->join('l.productId','p')
            ->join('p.productDescriptionId','d')
            ->where('l.merchantId = ?1')   
            ->setParameter(1,(int)$id);
            $query=$qb->getQuery();
            $result=$query->getResult();
       //  dump($result);die;
            return $result;
        
    }
    
    public function findProductDetails($Id)
    {
        $em = $this->getEntityManager();
        
        $qb = $em->createQueryBuilder();
        $qb->select('l.productName,l.productPrice,d.color,d.ramSize,d.productCompleteInfo')
        ->from('Common\Model\Product','l')
        ->join('l.productDescriptionId','d')
       // ->innerJoin('p.productDescriptionId','d')
        ->where('l.id = ?1')
        ->setParameter(1,(int)$Id);
        $query=$qb->getQuery();
       
        $result=$query->getResult();
      //  dump($result);die;
        return $result;
    }
 
    public function findIMEI($id) {
        
        $em = $this->getEntityManager();
        
        $qb = $em->createQueryBuilder();
    
        $qb->select('pi.productIMEI')
        ->from('Common\Model\Product_Detail_List','pi')
        ->join('pi.productId','p')
        ->where('p.id= ?1')
        ->setParameter(1,$id);
        $query=$qb->getQuery();
       
        $result=$query->getResult();
      
        return $result;
       
    }
 
 }




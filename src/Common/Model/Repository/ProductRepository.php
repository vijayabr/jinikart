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
    
    public function productAdvancedSearch($filterData)
    {   
        $filterQuery = $this->createQueryBuilder('p')
        ->select('p', 'pd', 'c', 'b')
        ->leftJoin('p.categoryId', 'c')
        ->leftJoin('p.brandId', 'b')
        ->leftJoin('p.productDescriptionId', 'pd')
        ->orderBy('p.createdAt', 'desc');        
        
        foreach ($filterData as $filter => $data) {
            if (isset($data) && !empty($data) ) {
                switch ($filter) {
                    case 'minprice':
                        $filterQuery = $filterQuery->andWhere('p.productPrice  >=:minprice')
                        ->setParameter('minprice', $data);
                        break;
                        
                    case 'maxprice':
                        $filterQuery = $filterQuery->andWhere('p.productPrice  <=:maxprice')
                        ->setParameter('maxprice', $data);
                        break;
                        
                    case 'category':
                        $filterQuery = $filterQuery->andWhere('c.categoryName LIKE :category' )
                        ->setParameter('category', "%".$data."%");
                        break;                        
                    case 'brand':
                        $filterQuery = $filterQuery->andWhere('b.brandName LIKE :brand' )
                        ->setParameter('brand', "%".$data."%");
                        break;                   
                        
                    case 'ramsize':
                        switch($data){
                            case 'KB':
                                $filterQuery = $filterQuery->andWhere('pd.ramSize LIKE :ramsize')
                                ->setParameter('ramsize',"%".$data."%" );
                                break;
                            case '1GB':
                                $filterQuery = $filterQuery->andWhere('pd.ramSize =:ramsize')
                                ->setParameter('ramsize',$data);
                                break;
                            case '2GB':
                                $filterQuery = $filterQuery->andWhere('pd.ramSize =:ramsize')
                                ->setParameter('ramsize',$data);
                                break;
                            case '3GB':
                                $filterQuery = $filterQuery->andWhere('pd.ramSize =:ramsize')
                                ->setParameter('ramsize',$data);
                                break;
                            case '4GB':
                                $filterQuery = $filterQuery->andWhere('pd.ramSize >=:ramsize')
                                ->setParameter('ramsize',$data);
                                break;                        
                        }
                        break;    
                    case 'discount':
                        switch($data){
                            case '1':
                                $filterQuery = $filterQuery->andWhere('p.productDiscount < :maxdiscount')
                                ->setParameter('maxdiscount',"10" );
                                break;
                            case '10':
                                $filterQuery = $filterQuery->andWhere('p.productDiscount >= :mindiscount and p.productDiscount < :maxdiscount')
                                ->setParameter('mindiscount',$data)
                                ->setParameter('maxdiscount',"25");
                                break;
                            case '25':
                                $filterQuery = $filterQuery->andWhere('p.productDiscount >= :mindiscount and p.productDiscount< :maxdiscount')
                                ->setParameter('mindiscount',$data)
                                ->setParameter('maxdiscount',"35");
                                break;
                            case '35':
                                $filterQuery = $filterQuery->andWhere('p.productDiscount >= :mindiscount and p.productDiscount< :maxdiscount')
                                ->setParameter('mindiscount',$data)
                                ->setParameter('maxdiscount',"50");
                                break;
                            case '50':
                                $filterQuery = $filterQuery->andWhere('p.productDiscount >= :mindiscount')
                                ->setParameter('mindiscount',$data);
                                break;                                
                        }
                       break;
                    case 'camera':
                        switch($data){
                            case 'front':
                                $filterQuery = $filterQuery->andWhere('pd.camera LIKE :camera')
                                ->setParameter('camera',"%".$data."%" );
                                break;
                            case 'back':
                                $filterQuery = $filterQuery->andWhere('pd.camera LIKE :camera')
                                ->setParameter('camera',"%".$data."%" );
                                break;
                            case 'dual':
                                $filterQuery = $filterQuery->andWhere('pd.camera LIKE :camera')
                                ->setParameter('camera',"%".$data."%" );
                                break;                     
                        }
                        break;    
                }
            }
        }
        $filterQuery = $filterQuery->getQuery()->useQueryCache(true);
        return $filterQuery->getResult();
    }  
   
    public function findAllProductDetails($merchantId)
    {
          $em = $this->getEntityManager();
         
            $qb = $em->createQueryBuilder();
            $qb->select('l')
            ->from('Common\Model\Product_Detail_List','l')
            ->join('l.productId','p')
            ->join('p.productDescriptionId','d')
            ->where('l.merchantId = ?1')   
            ->setParameter(1,(int)$merchantId);
            $query=$qb->getQuery();
         
            $result=$query->getResult();
            return $result;
        
    }
    public function findUniqueProductCount($id)
    {
        
        $em = $this->getEntityManager();
        
        $qb = $em->createQueryBuilder();
        $qb->select('distinct l.productCount')
        ->from('Common\Model\Product','l')
        ->where('l.id=?1')
        ->setParameter('1', $id);
        $query=$qb->getQuery();
        dump($query);die;
    }
 }




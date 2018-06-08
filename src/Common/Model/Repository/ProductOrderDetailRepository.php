<?php

namespace Common\Model\Repository;

/**
 * ProductOrderDetailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductOrderDetailRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function productOrders($merchant){
        $query = $this->createQueryBuilder('pod')
        ->select('pod.id','pi.productIMEI','p.productName','p.productPrice','p.productDiscount','pd.color','pd.ramSize',
            'pd.productCompleteInfo','pod.deliveryDate','c.fname','c.lname','c.lname','c.email','c.mobileNo',
            'po.orderedDate','a.addressLine1','a.addressLine2','s.stateName','co.countryName','a.pincode','po.orderStatus',
            '((100-p.productDiscount)*p.productPrice)/100 As price')
        ->leftJoin('pod.cartListId', 'cl')
        ->leftJoin('pod.productOrderId', 'po')
        ->leftJoin('po.customerId', 'c')
        ->leftJoin('cl.productIMEI', 'pi')
        ->leftJoin('pi.productId', 'p')  
        ->leftJoin('po.deliveryAddress','a' )
        ->leftJoin('a.stateId', 's')
        ->leftJoin('a.countryId', 'co')
        ->leftJoin('p.productDescriptionId', 'pd')
        ->andWhere('pi.merchantId=:merchant')
        ->setParameter('merchant', $merchant);
        $query = $query->getQuery()->useQueryCache(true);
        return $query->getResult();
        
    }

        public function findInvoiceDetails($id){
            
            $em = $this->getEntityManager();
            $qb = $em->createQueryBuilder();
            $qb->select('pod','po.orderedDate','cl','pd,p.productName','p.productPrice','ct','c.fname','a.addressLine1','s.stateName','cy.countryName')
            ->from('Common\Model\ProductOrderDetail','pod')
            ->join('pod.productOrderId','po')
            ->join('pod.cartListId','cl')
            ->innerjoin('cl.cartId','ct')
            ->innerjoin('ct.customerId','c')
            ->join('c.addressId','a')
            ->innerJoin('a.stateId','s')
            ->innerJoin('a.countryId','cy')
            ->join('cl.productIMEI','pd')
            ->join('pd.productId','p')
            ->where('p.id= ?1')
            ->setParameter('1',(int)$id);
            
            $query=$qb->getQuery();
            $result=$query->getResult();
         //  dump($result);die;
            return $result;
        }
    

    
    
    public function productOrder($merchant,$orderId){
        $query = $this->createQueryBuilder('pod')
        ->select('pod.id','pi.productIMEI','p.productName','p.productPrice','p.productDiscount','pd.color','pd.ramSize',
            'pd.productCompleteInfo','pod.deliveryDate','c.fname','c.lname','c.lname','c.email','c.mobileNo',
            'po.orderedDate','a.addressLine1','a.addressLine2','s.stateName','cy.countryName','a.pincode','po.orderStatus',
            '((100-p.productDiscount)*p.productPrice)/100 As price')
            ->leftJoin('pod.cartListId', 'cl')
            ->leftJoin('pod.productOrderId', 'po')
            ->leftJoin('po.customerId', 'c')
            ->leftJoin('cl.productIMEI', 'pi')
            ->leftJoin('pi.productId', 'p')
            ->leftJoin('po.addressId','a' )
            ->leftJoin('a.stateId', 's')
            ->leftJoin('a.countryId', 'cy')
            ->leftJoin('p.productDescriptionId', 'pd')
            ->andWhere('pi.merchantId=:merchant')
            ->setParameter('merchant', $merchant)
            ->andWhere('pod.id=:id')
            ->setParameter('id', $orderId);
            $query = $query->getQuery()->useQueryCache(true);
            return $query->getResult();
    }
    
}



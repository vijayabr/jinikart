<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Common\Model\ProductOrderDetail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\Query\Expr\Math;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CommonServiceBundle\Helper\fileUploader;
use CommonServiceBundle\Helper\pdfFilegenerator;

class ReportController extends Controller
{
            
   //Function for viewing placed orders
    /**
     * @Route("/merchant/order",name="_order_page");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function OrderListAction(Request $request)
    {
        try{
        $merchant=$this->getUser();  
        $em = $this->getDoctrine()->getManager();
        $orders=$em->getRepository('Model:ProductOrderDetail')->productOrders($merchant);       
        }catch(\Exception $exception){
            
            echo " Error while listing orders";
        }
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant,'orders'=>$orders));      
    }
    
    
    
    //Function for invoice pdf generation
    /**
     * @Route("/merchant/invoice",name="invoicePdf_page");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function invoicePdfGeneratorAction(Request $request)
    {  
        try{
            $order="";
            $merchant=$this->getUser();
            $filegenerator= new pdfFilegenerator($this->container);
            $filegenerator->pdffilegenerator($merchant,$merchant->getcompanyName(),$order);   
        }catch(\Exception $exception){
            
            echo " Error in invoice generation";
        }
        return new Response("save the file");
    }

    //Function for generating order pdf info
    /**
     * @Route("/merchant/orderInvoice/{order}",name="orderinvoicePdf_page");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function OrderPdfAction($order, Request $request)
    {   
        try{
        $merchant=$this->getUser();
        $filegenerator= new pdfFilegenerator($this->container);
        $filegenerator->pdffilegenerator($merchant,$merchant->getcompanyName(),$order);        
        }catch(\Exception $exception){          
            echo " Error while generating order pdf";
        }
        return new Response("save the file");
    } 
    //Function for accepting customer order
    /**
     * @Route("/merchant/orderaccept/{order}",name="orderaccept_page");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function OrderAcceptAction($order, Request $request)
    {
        $time = new \DateTime();     
        try{
        $em = $this->getDoctrine()->getManager();
        $productOrderDetail=$em->getRepository('Model:ProductOrderDetail')->find($order);
        $productOrderDetail->setOrderStatus("processed"); 
        $productOrderDetail->setDeliveryDate($time);
        $em->persist($productOrderDetail);
        $em->flush();
        }catch(\Exception $exception){            
            echo " Error in order acception";
        }
        return $this->redirectToRoute('_order_page'); 
    }
    //Function for rejecting customer order
    /**
     * @Route("/merchant/orderreject/{order}",name="orderreject_page");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function OrderRejectAction($order, Request $request)
    {
        try{
        $em = $this->getDoctrine()->getManager();
        $productOrderDetail=$em->getRepository('Model:ProductOrderDetail')->find($order);
        $productOrderDetail->setOrderStatus("rejected");
        $em->persist($productOrderDetail);
        $em->flush();
        }catch(\Exception $exception){
            
            echo " Error in order rejection";
        }
        return $this->redirectToRoute('_order_page');
    }    
    //Function for generating order notifications
    /**
     * @Route("/merchant/notification",name="ordernotification_page");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
  public function notificationAction(Request $request)
  { $result=array(); 
    try{
    $merchant=$this->getUser();
    $em = $this->getDoctrine()->getManager();
    $products= $em->getRepository('Model:ProductOrderDetail')->productNotification($merchant);
    if ($products){
        $result['status']="success";    
    $totalcount = count($products);  
    $requestedProductCount=3;
    $deliveredProductCount=0;
    $processedProductCount=0;
    foreach ($products as $product){dump($product);
        if($product['orderStatus']=="requested"){
            $requestedProductCount +=1;            
        }
        elseif ($product['orderStatus']=="processed"){
            $processedProductCount +=1;
            
        }elseif($product['orderStatus']=="delivered"){
         
            $deliveredProductCount +=1;
        }else {
            $totalcount -=1;
        }
    }
    }
    $result['data']['totalcount']=$totalcount;
    $result['data']['requestedProductCount']=$requestedProductCount;
    $result['data']['deliveredProductCount']=$deliveredProductCount;
    $result['data']['processedProductCount']=$processedProductCount;
    }catch(\Exception $exception){
        
        echo " Error in notification";
     }
     return new JsonResponse($result);
  }    
}

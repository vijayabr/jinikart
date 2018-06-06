<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Common\Model\ProductOrderDetail;

class ReportController extends Controller
{
    public function invoiceInformation($merchant){
        $em = $this->getDoctrine()->getManager();
        $pr =$em->getRepository('Model:Product')->findAll();
        $products=$em->getRepository('Model:Product_Detail_List')->findBy(["merchantId"=>$merchant]);
        $orders=$em->getRepository('Model:ProductOrderDetail')->findAll();
        dump($orders);
        dump($products);    
        die;
    }
    
    
    /**
     * @Route("/merchant/order",name="_order_page");
     * @param Request $request
     */
    public function OrderListAction(Request $request)
    {
        
        $merchant=$this->getUser();
        $details= $this->invoiceInformation($merchant);      
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant));
        
    }
    
    
    /**
     * @Route("/merchant/pdf",name="_pdfGenerator_page");
     * @param Request $request
     */
    public function PdfGeneratorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $merchant=$this->getUser();
        
        $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp']);
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output();
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant));
        
    }
}

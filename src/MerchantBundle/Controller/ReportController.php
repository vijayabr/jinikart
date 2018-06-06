<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
    /**
     * @Route("/merchant/order",name="_order_page");
     * @param Request $request
     */
    public function OrderListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $merchant=$this->getUser();
        
       
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

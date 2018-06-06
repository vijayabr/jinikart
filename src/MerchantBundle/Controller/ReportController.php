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
        
        require_once __DIR__ . '/vendor/autoload.php';
        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output();
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant));
        
    }
}

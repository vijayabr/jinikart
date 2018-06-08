<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Common\Model\ProductOrderDetail;

class ReportController extends Controller
{
    public function pdffilegenerator($data,$merchant){
        $filename=$merchant.".pdf";
        
        $directoryPath=$this->getParameter('company_image_directory');
        $mpdf = new \Mpdf\Mpdf(['tempDir' => $directoryPath,'format'=>'A4','mode' => 'utf-8','orientation' => 'L']);
        $mpdf->WriteHTML($data);        
        $mpdf->Output($filename,'D');
        $mpdf->Output($directoryPath . $filename, \Mpdf\Output\Destination::FILE);
    }
        
   
    /**
     * @Route("/merchant/order",name="_order_page");
     * @param Request $request
     */
    public function OrderListAction(Request $request)
    {
        
        $merchant=$this->getUser();  
        $em = $this->getDoctrine()->getManager();
        $orders=$em->getRepository('Model:ProductOrderDetail')->productOrders($merchant);       
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant,'orders'=>$orders));
        
    }
    
    public function invoicePdfGeneratorData($merchant,$order){
        $em = $this->getDoctrine()->getManager();
        if($order){
            $orders=$em->getRepository('Model:ProductOrderDetail')->productOrder($merchant,$order);
            
        }
        else {
        $orders=$em->getRepository('Model:ProductOrderDetail')->productOrders($merchant);
        }
        if($orders){
            $msg = "<h1>Your orders list:</h1>";
            foreach($orders as $order){
                $date=$order["orderedDate"];
                $ordereddate= $date->format('Y-m-d H:i:s');
                $msg=$msg." <p><h4>Customer Name  :      ".$order["fname" ]."    ".$order["lname"].
                "<br></h4><h5>Mobile Number :    ". $order["mobileNo"].
                "<br>Email   :     ".$order["email"]."<br>Delivery address :".$order["addressLine1"]."<br>                                                                                            ".
                $order["addressLine2"]."<br>".$order["stateName"]."    ".$order["countryName"]."       ".$order["pincode"].
                "<br></h5><h5>Product price :       ".$order["productPrice"].
                "<br>Discount   :     ".$order["productDiscount"].
                "%<br>Selling price :       ".$order["price"].
                "<br>Ordered date   :       ".$ordereddate.
                "<br>Order Status   :       ".$order["orderStatus"].
                "<br></h5><h4>Product Information   :       ".$order["productName"].
                "</h4><h5>IMEI number   :         ".$order["productIMEI"]."          Color      :        "
                    .$order["color"] ."         pramsize       :         ".$order["ramSize"].
                    "<br>About Product      :           </h5><h6>".$order["productCompleteInfo"]."</h6></p>";
            }
        }else{
            $msg="No order placed";
        }
        return  $msg;
    }
    
    
    /**
     * @Route("/merchant/invoice",name="invoicePdf_page");
     * @param Request $request
     */
    public function invoicePdfGeneratorAction(Request $request)
    {   $order="";
        $merchant=$this->getUser();
        $msg=$this->invoicePdfGeneratorData($merchant,$order);
        $this->pdffilegenerator($msg,$merchant->getCompanyName());
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant));
        
    }
    
    /**
     * @Route("/merchant/orderInvoice/{order}",name="orderinvoicePdf_page");
     * @param Request $request
     */
    public function OrderPdfAction($order, Request $request)
    {   
        $merchant=$this->getUser();
        $msg=$this->invoicePdfGeneratorData($merchant,$order);
        $this->pdffilegenerator($msg,$merchant->getcompanyName());
        return $this->render("@Merchant/Order/orderlist.html.twig",array('merchant'=> $merchant));
        
    }    
    
   
}

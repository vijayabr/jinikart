<?php 
namespace CommonServiceBundle\Helper;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Common\Model\Address;
use Symfony\Component\HttpFoundation\Response;


class pdfFilegenerator{
   
    public  $container;
  
    
    public function __construct(Container $container){
        $this->container=$container;             
    }
    
    //Function for generating pdf file
    public function pdffilegenerator($user,$merchant,$order=null){
        try{
            $msg=$this->invoicePdfGeneratorData($user,$order);
            $filename=$merchant.$order.".pdf";
            $directoryPath=$this->container->getParameter('product_file_directory');
            $mpdf = new \Mpdf\Mpdf(['tempDir' => $directoryPath,'format'=>'A4','mode' => 'utf-8','orientation' => 'L']);
            $mpdf->WriteHTML($msg);
            $mpdf->Output($directoryPath.'/'."temp.pdf",'F');
            //download the file in browser to show
            $mpdf->Output($filename,'D');
            $fileUpload = new fileUploader($this->container);
            $dest="invoice";
            $fileUpload->pdfFileUpload($filename,$dest);
            return $filename;
        }
        catch (\Exception $e){
            dump($e->getMessage());die;
        }
        
    }
    //Function for data generation for pdf
    public function invoicePdfGeneratorData($merchant,$order){
        try{
            $em =  $this->container->get('doctrine')->getEntityManager();
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
        }catch(\Exception $exception){
            
            echo " Error in invoice generation data";
        }
        return  $msg;
    }        
}
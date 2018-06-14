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


class ReportController extends Controller
{
    public function pdffilegenerator($data,$merchant){
        $filename=$merchant.".pdf";
        
        $directoryPath=$this->getParameter('product_file_directory');
        $mpdf = new \Mpdf\Mpdf(['tempDir' => $directoryPath,'format'=>'A4','mode' => 'utf-8','orientation' => 'L']);
        $mpdf->WriteHTML($data);        
        $mpdf->Output($directoryPath.'//'.$filename,'F');
        //download the file in browser to show
        $mpdf->Output($filename,'D');

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
        return new Response("save the file");
        
    }
    
    /**

     * @Route("/merchant/excel/{id}",name="excel_page");
     * @param Request $request
     */
    public function indexAction(Request $request,$id)
    {
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getDefaultStyle()->getFont()->setName('Arial Black');
        $phpExcelObject->getDefaultStyle()->getFont()->setSize(12);
        $phpExcelObject->getProperties()->setCreator("liuggio")
       
        ->setSubject("Product Document")
        ->setDescription("Stock Document, generated using PHP classes.");
      
        $em = $this->getDoctrine()->getManager();     
        $product = $em->getRepository('Model:Merchant')->findAllDetails(['id'=> $id]);  
       //  dump($product);die;
        $phpExcelObject->setActiveSheetIndex(0);
        $sheet = $phpExcelObject->getActiveSheet(0);
        $sheet
        ->setCellValue('A1', 'Product Name')
        ->setCellValue('B1', 'ProductIMEI')
        ->setCellValue('C1', 'ProductCount')
        ->setCellValue('D1', 'Remaining');    
        $sheet->setTitle("Stock");
        $rowCount = 2;
        foreach ($product as $value){
             $phpExcelObject->getActiveSheet(0)->SetCellValue('A'.$rowCount, $value['productName']);
             $phpExcelObject->getActiveSheet(0)->SetCellValue('B'.$rowCount, $value['productIMEI']); 
             $phpExcelObject->getActiveSheet(0)->SetCellValue('C'.$rowCount, $value['productCount']);     
             $rowCount++;
        }
           $objWorkSheet = $phpExcelObject->createSheet(1)->setTitle("Invoice");
           $objWorkSheet  = $phpExcelObject->getSheet(1);
         /*  foreach(range('A','E') as $columnID) {
               $objWorkSheet->getSheetState(1)->getColumnDimensionByColumn($columnID)
               ->setAutoSize(true);
           }*/
           
           $objWorkSheet->setCellValue('A1', 'Customer Name')
          ->setCellValue('B1', 'Product Name')
          ->setCellValue('C1', 'Price')
          ->setCellValue('D1', 'Ordered Date')
         
          ->setCellValue('E1', 'Shipping Address');  
          $em = $this->getDoctrine()->getManager();
          $invoice = $em->getRepository('Model:ProductOrderDetail')->findInvoiceDetails(['id'=> $id]); 
         // dump($invoice);die;
          //dump($invoice['Product.productName']);die;
          $rowCount = 2;
          foreach ($invoice as $value){
              $phpExcelObject->getSheet(1)->SetCellValue('A'.$rowCount, $value['fname']);
              $phpExcelObject->getSheet(1)->SetCellValue('B'.$rowCount, $value['productName']);
              $phpExcelObject->getSheet(1)->SetCellValue('C'.$rowCount, $value['productPrice']);
              $phpExcelObject->getSheet(1)->SetCellValue('D'.$rowCount, $value['orderedDate']);
              $phpExcelObject->getSheet(1)->SetCellValue('E'.$rowCount, $value['addressLine1'].$value['stateName'].$value['countryName']);
              $rowCount++;
          }
        
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // $writer->save('web/Excel/stock-invoice-file.xls');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'stock-invoice-file.xls'
            );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        
        return $response;
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
        return new Response("save the file");
        
    } 
    
    
    /**
     * @Route("/merchant/orderaccept/{order}",name="orderaccept_page");
     * @param Request $request
     */
    public function OrderAcceptAction($order, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productOrderDetail=$em->getRepository('Model:ProductOrderDetail')->find($order);
        $productOrderDetail->setOrderStatus("processed");        
        $em->persist($productOrderDetail);
        $em->flush();
        return $this->redirectToRoute('_order_page');        
        
    }
    
    /**
     * @Route("/merchant/orderreject/{order}",name="orderreject_page");
     * @param Request $request
     */
    public function OrderRejectAction($order, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $productOrderDetail=$em->getRepository('Model:ProductOrderDetail')->find($order);
        $productOrderDetail->setOrderStatus("rejected");
        $em->persist($productOrderDetail);
        $em->flush();
        return $this->redirectToRoute('_order_page');
    }    
   
    /**
     * @Route("/merchant/notification",name="ordernotification_page");
     * @param Request $request
     */
    public function notificationAction(Request $request)
    {   
    $merchant=$this->getUser();
    $em = $this->getDoctrine()->getManager();
    $products= $em->getRepository('Model:ProductOrderDetail')->productNotification($merchant);
    if ($products){
        $result=array();
        $result['status']="success";
    
    $totalcount = count($products);  
    $requestedProductCount=0;
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
    
    return new JsonResponse($result);
    
    }
    
}

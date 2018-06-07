<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
       // $writer->save('web/Excel/stock-file.xlsx');
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
}

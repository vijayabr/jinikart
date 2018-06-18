<?php 
namespace CommonServiceBundle\Helper;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class pdfFilegenerator{
   
    public  $container;
  
    
    public function __construct(Container $container){
        $this->container=$container;             
    }


public function excelsheetformerchant($merchant)
    { 
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getDefaultStyle()->getFont()->setName('Arial Black');
        $phpExcelObject->getDefaultStyle()->getFont()->setSize(12);
        $phpExcelObject->getProperties()->setCreator("liuggio")
        ->setSubject("Product Document")
        ->setDescription("Stock Document, generated using PHP classes.");
        
        //For stock report
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('Model:Merchant')->findAllDetails(['id'=> $id]);
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
        //For invoice report
        $objWorkSheet = $phpExcelObject->createSheet(1)->setTitle("Invoice");
        $objWorkSheet  = $phpExcelObject->getSheet(1);
        $objWorkSheet->setCellValue('A1', 'Customer Name')
        ->setCellValue('B1', 'Product Name')
        ->setCellValue('C1', 'Price')
        ->setCellValue('D1', 'Ordered Date')
        ->setCellValue('E1', 'Shipping Address');
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('Model:Merchant')->findInvoiceDetails(['id'=> $id]);
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
        $writer->save('temp/stock-invoice-file1.xls');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'stock-invoice-file.xls');
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        
        return new Response("Excel sheet generated");
        return $response; 
}
}
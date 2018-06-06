<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
    
    /**
     * @Route("/merchant/excel/{id}",name="excel_page");
     * @param Request $request
     */
    public function indexAction(Request $request,$id)
    {
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getDefaultStyle()->getFont()->setName('Arial Black');
        $phpExcelObject->getDefaultStyle()->getFont()->setSize(14);
        $phpExcelObject->getProperties()->setCreator("liuggio")
      //  ->setLastModifiedBy("Giulio De Donato")
        ->setTitle("Stock Document")
        ->setSubject("Product Document")
        ->setDescription("Stock Document, generated using PHP classes.");
     //   ->setKeywords("office 2005 openxml php")
     //   ->setCategory("Test result file");
      
        $em = $this->getDoctrine()->getManager(); 
      
        $product = $em->getRepository('Model:Merchant')->findAllDetails(['id'=> $id]);

      
        $rowCount = 2;
        foreach ($product as $value){
             $phpExcelObject->getActiveSheet()->SetCellValue('A'.$rowCount, $value['productName']);
     //       $phpExcelObject->getActiveSheet()->SetCellValue('B'.$rowCount, $row['age']);
       //     $phpExcelObject->getActiveSheet()->SetCellValue('C'.$rowCount, $row['name']);
         //   $phpExcelObject->getActiveSheet()->SetCellValue('D'.$rowCount, $row['name']);
            $rowCount++;
        }
         $phpExcelObject->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Product Name')
        ->setCellValue('B1', 'ProductIMEI')
        ->setCellValue('C1', 'ProductCount')
        ->setCellValue('D1', 'Remaining');
        
        
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
        
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $writer->save('web/Excel/stock-file.xlsx');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'stock-file.xls'
            );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        
        return $response;
    }
}

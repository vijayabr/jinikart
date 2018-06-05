<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Common\Model\Product;
use MerchantBundle\Form\AddProductType;
use Common\Model\Category;
use Common\Model\Brand;
use Common\Model\Product_Detail_List;
use Common\Model\Product_Description;
use Common\Model\Product_Photo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MerchantBundle\Form\UpdateProductType;
use Common\Model\Merchant;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ProductManagementController extends Controller
{
    /**
     * @Route("/add",name="add_products");
     * @param Request $Request
     */
    public function addProductAction(Request $request){
        
        $form = $this->createForm(AddProductType::class);
        $form->handleRequest($request);     
        
          try{     
            //  $id="";
            if ($form->isSubmitted() && $form->isValid()) {
   
                 $em = $this->getDoctrine()->getManager(); 
              //   $product=$em->getRepository('Model:Product')->getDataForm($form);
                 $id=$form->getData()["merchantId"];
                 $name=$form->getData()["product_name"];
                 $price = $form->getData()["product_price"];
                 $imei = $form->getData()["productIMEI"];  
                 $discount = $form->getData()["product_discount"];
                 $category = $form->getData()["categoryName"]; 
                 $brand = $form->getData()["brandName"];
                 $color = $form->getData()["color"];
                 $ram = $form->getData()["ram_size"];
                 $cam = $form->getData()["camera"];
                 $info = $form->getData()["product_complete_info"];
                 $image = $form->getData()["product_photo"];
                 
                 $merchant= new Merchant();
                 $merchant=$em->getRepository('Model:Merchant')->findOneBy(['id'=>$id]);
               
                 $descp = new Product_Description();
                 $descp->setColor($color);
                 $descp->setRamSize($ram);
                 $descp->setCamera($cam);
                 $descp->setProductCompleteInfo($info);            
                 $em->persist($descp);
                 $em->flush();           
                 
                 $product= new Product();
                 $product->setProductName($name);
                 $product->setProductDiscount($discount);
                 $product->setProductPrice($price);
                 $product->setBrandId($brand);
                 $product->setCategoryId($category);
                 $product->setProductDescriptionId($descp);
                 $product->setMerchantId($merchant);
               
                 $em->persist($product);
                 $em->flush();
                
                 $photo= new Product_Photo();
                 $imageName =  $product->getProductName(). '.' . $image->guessExtension();
                 $image->move($this->getParameter('product_image_directory'),$imageName);
                 $photo->setPhotoName($imageName);
                 $photo->setProductId($product);
                 $em->persist($photo);
                 $em->flush();
                  
                 $imei1= new Product_Detail_List();          
                 $imei1->setProductIMEI($imei);              
                 $imei1->setProductId($product);  
                 $em->persist($imei1);
                 $em->flush();
                
               
              return new Response("Added to Database");
              return $this->render("@Merchant/Default/homepage.html.twig");
              
            }
            return $this->render("@Merchant/Default/add.html.twig",array('form'=> $form->createView()));
                 }catch(\Exception $exception){
 
                    return new Response($exception);
                    die;
        }
  }
    
    /**
     * @Route("/list/{id}",name="list_products");
     * @param Request $Request
     */
    
  public function listProductAction(Request $Request,$id){
        
          $merchantId = $Request->request->get('id');
         
          $product = $this->getDoctrine()->getRepository('Model:Product')->findAllProductDetails(['id'=>$merchantId]);
        
          return $this->render("@Merchant/Default/list.html.twig",array('product'=>$product,'merchant'=>$id));
        //'imei'=>$imei,'cat'=>$cat,'brand'=>$brand,'descp'=>$descp));
    }
    
    /**
     * @Route("/coupon/{id}",name="coupon");
     * @param Request $request
     */
    public function couponGenerateAction(Request $Request,$length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',$id){
//              $id = $Request->request->get('id');
             $charactersLength = strlen($characters);
             $randomString = '';
             for ($i = 0; $i < $length; $i++) {
             $randomString .= $characters[rand(0, $charactersLength - 1)];
             }
             //return new Response($randomString);
             $em=$this->getDoctrine()->getManager();
             $product= new Product();
             $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id] );
             $product->setCoupon($randomString);
           
             $em->persist($product);
             $em->flush();
             
             return  $this->redirectToRoute("list_products",array('id'=>$id)); 
             
    }
/**
     * @Route("/update",name="update");
     * @param Request $request
     */
public function updateAction(Request $request)
{
        $form = $this->createForm(UpdateProductType::class);      
        $form->handleRequest($request);                       
           try{
               
                if ($form->isSubmitted() && $form->isValid()) 
                {     
                  $em = $this->getDoctrine()->getManager();
                  $product= new Product();
                  $product=$em->getRepository('Model:Product')->findOneBy(['id'=>'1']);
                  $descp=new Product_Description();
                  $descp=$em->getRepository('Model:Product_Description')->findOneBy(['id'=>'1']);
                   
                  $name=$form->getData()["product_name"];
                  $price = $form->getData()["product_price"];
                  $discount = $form->getData()["product_discount"];
                  $category = $form->getData()["categoryName"];
                  $brand = $form->getData()["brandName"];
                  $color = $form->getData()["color"];
                  $ram = $form->getData()["ram_size"];
                  $cam = $form->getData()["camera"];
                  $info = $form->getData()["product_complete_info"];
              //  $image = $form->getData()["product_photo"];
          
                  if($product->getProductName()!= $name||$product->getProductPrice()!= $price||
                      $product->getDiscount()!= $discount||$descp->getColor()!=$color||$descp->getRamSize()!=$ram||
                      $descp->getCamera()!=$cam||$descp->getProductCompleteInfo()!=$info){
                    
                  $descp->setColor($color);
                  $descp->setRamSize($ram);
                  $descp->setCamera($cam);
                  $descp->setProductCompleteInfo($info);
                    
                  $em->persist($descp);
                  $em->flush();
                   
                
                  $product->setProductName($name);
                  $product->setProductDiscount($discount);
                  $product->setProductPrice($price);
                  $product->setBrandId($brand);
                  $product->setCategoryId($category);
                  $product->setProductDescriptionId($descp);
                    
                  $em->persist($product);
                  $em->flush();
                  }
//                 $photo= new Product_Photo();
//                 $imageName =$product->getProductName(). '.' . $image->guessExtension();
//                 $image->move($this->getParameter('product_image_directory'),$imageName);
//                 $photo->setPhotoName($imageName);
//                 $photo->setProductId($product);
//                 $em->persist($photo);
//                 $em->flush();
                  return $this->render("@Merchant/Default/updated.html.twig",array('product'=>$product,'descp'=>$descp)
                   );
               }

              return $this->render("@Merchant/Default/update.html.twig",array('form'=> $form->createView()));
         
              
           }catch(\Exception $exception){
                  
          return new Response($exception);
          die;
         }
      
}
   
    
  
    /**
     * @Route("/details",name="company_details");
     * @param Request $request
     */
    public function detailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $merchant=$em->getRepository('Model:Merchant')->findOneBy(['id'=>1]);
       
        return $this->render("@Merchant/Account/detail.html.twig",array('merchant'=> $merchant));
        
    }
    
    /*
     * @Route("/test");
     * 
     */
    public function excelAction()
    {
        // ask the service for a excel object
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        
        $phpExcelObject->getProperties()->setCreator("liuggio")
        
        ->setTitle(" Test Document")
        ->setSubject("Office 2005 XLSX Test Document")
        ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
        ->setKeywords("office 2005 openxml php")
        ->setCategory("Test result file");
        $phpExcelObject->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Hello')
        ->setCellValue('B2', 'world!');
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
        
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $writer->save('/path/to/save/test.xlsx');
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'PhpExcelFileSample.xlsx'
            );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        
        return $response;
      
    }
}

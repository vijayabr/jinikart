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
            
            if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
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
        
                $descp = new Product_Description();
                $descp->setColor($color);
                $descp->setRamSize($ram);
                $descp->setCamera($cam);
                $descp->setProductCompleteInfo($info);
            
                 $em->persist($descp);
                 $em->flush();  
                
                $pro= new Product();
                $pro->setProductName($name);
                $pro->setProductDiscount($discount);
                $pro->setProductPrice($price);
                $pro->setBrandId($brand);
                $pro->setCategoryId($category);
                $pro->setProductDescriptionId($descp);
              
                $em->persist($pro);
                $em->flush();
                
                $photo= new Product_Photo();
                $imageName =  $pro->getProductName(). '.' . $image->guessExtension();
                $image->move($this->getParameter('product_image_directory'),$imageName);
                $photo->setPhotoName($imageName);
                $photo->setProductId($pro);
                $em->persist($photo);
                $em->flush();
                
               
                $imei1= new Product_Detail_List();
                $imei1->setProductIMEI( $imei); 
                $imei1->setProductId($pro);
              
                $em->persist($imei1);
                $em->flush();
           
            }
            return $this->render("@Merchant/Default/add.html.twig",array('form'=> $form->createView()));      
       }catch(\Exception $exception){
 
           return new Response($exception);
           die;
       }
  }
    
    /**
     * @Route("/list",name="list_products");
     * @param Request $Request
     */
    
    public function listProductAction(Request $Request){
        
        $em=$this->getDoctrine()->getManager();
        $pro=$em->getRepository('Model:Product')->findOneBy(['id'=>'1']);
        
        $imei=$em->getRepository('Model:Product_Detail_List')->findOneBy(['id'=>'1']);
        
        $cat=$em->getRepository('Model:Category')->findOneBy(['id'=>'1']);
        
        $brand=$em->getRepository('Model:Brand')->findOneBy(['id'=>'1']);
        
        $descp=$em->getRepository('Model:Product_Description')->findOneBy(['id'=>'1']);

        return $this->render("@Merchant/Default/list.html.twig",array('product'=>$pro,'imei'=>$imei,
            'cat'=>$cat,'brand'=>$brand,'descp'=>$descp));
    }
    
    /**
     * @Route("/coupon",name="coupon");
     * 
     */
    public function couponGenerateAction(Request $Request,$length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',$id){
        
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
             
             return $this->redirectToRoute("add_products",array('message'=>"Coupon Generated"));         
    }
/**
     * @Route("/update",name="update");
     * @param Request $request
     */
public function updateAction(Request $request)
{
        $form = $this->createForm(UpdateProductType::class);      
        $form->handleRequest($request);             
       // dump($form);die;
   //     $descp = new Product_Description();
   //     $descp=$em->getRepository('Model:Product_Description')->findOneBy(['id'=>'1']);               
           try{
               
           //    dump($product);die;
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
        $em = $thisss->getDoctrine()->getManager();
        $merchant=$em->getRepository('Model:Merchant')->findOneBy(['id'=>1]);
       
        return $this->render("@Merchant/Account/detail.html.twig",array('merchant'=> $merchant));
        
    }
  
}

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CommonServiceBundle\Helper\ImageUploader;



class ProductManagementController extends Controller
{
    //Function for merchant to add products
    /**
     * @Route("/merchant/add",name="add_products");
     * @param Request $Request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function addProductAction(Request $request){
        $merchant = $this->getUser();
        $form = $this->createForm(AddProductType::class);
        $form->handleRequest($request);        
          try{     
              //if form is successfully submitted
                 if ($form->isSubmitted() && $form->isValid()) {  
                     //fetching data from the form
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
                 
                 $merchant= new Merchant();
                 $merchant=$em->getRepository('Model:Merchant')->findOneBy(['id'=>$id]);
               
                 $descp = new Product_Description();  //setting product description(Product Description Table)
                 $descp->setColor($color);
                 $descp->setRamSize($ram);
                 $descp->setCamera($cam);
                 $descp->setProductCompleteInfo($info);            
                 $em->persist($descp);
                 $em->flush();           
                 
                 $product= new Product();    //setting product info(Product Table)
                 $product->setProductName($name);
                 $product->setProductDiscount($discount);
                 $product->setProductPrice($price);
                 $product->setBrandId($brand);
                 $product->setCategoryId($category);
                 $product->setProductDescriptionId($descp); 
                 $em->persist($product);
                 $em->flush();
                
                 $photo= new Product_Photo();
            
                 //Uploading image
                 if($image){
                     $imageName =  $product->getProductName(). '.' . $image->guessExtension();                     
                     $dest ='productImage';
                     $fileUpload = new ImageUploader($this->container);
                     $fileUpload->imageFileUpload($image,$imageName,$dest);
                 }    
                 //$image->move($this->getParameter('product_image_directory'),$imageName);
                 $photo->setPhotoName($imageName);
                 $photo->setProductId($product);
                 $em->persist($photo);
                 $em->flush();                  
                 $imei1= new Product_Detail_List();   //setting product details(Product Detail List Table)
                 $imei1->setProductIMEI($imei);              
                 $imei1->setProductId($product); 
                 $imei1->setMerchantId($merchant);
                 $em->persist($imei1);
                 $em->flush();                   
                 return $this->render("@Merchant/Default/homepage.html.twig",array('merchant'=>$merchant));              
            }
            return $this->render("@Merchant/Default/add.html.twig",array('form'=> $form->createView(),'merchant'=>$merchant));
                 }catch(\Exception $exception){ 
                     echo " Error while adding products";
        }
  }
  //Function for listing added products
    /**
     * @Route("/merchant/list/{id}",name="list_products");
     * @param Request $Request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    
  public function listProductAction(Request $Request,$id){
      
      try{
      $merchant = $this->getUser();
      $product = $this->getDoctrine()->getRepository('Model:Product')->findAllProductDetails($id);
      $count= $this->getDoctrine()->getRepository('Model:Product_Detail_List')->findCount($id);  
      }catch(\Exception $exception){      
      echo " Error while listing products";
     }
     return $this->render("@Merchant/Default/list.html.twig",array('product'=>$product,'merchantId'=>$id,'merchant'=>$merchant,'count'=>$count));   
 }
 //Function for generating coupon 
    /**
     * @Route("/merchant/coupon/{id}",name="coupon");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function couponGenerateAction(Request $Request,$length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',$id){
         
        try{
             $charactersLength = strlen($characters);
             $randomString = '';
             for ($i = 0; $i < $length; $i++) {
             $randomString .= $characters[rand(0, $charactersLength - 1)]; //random string generation
             }        
             $em=$this->getDoctrine()->getManager();
             $product= new Product();  //setting coupon to product table
             $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id] );            
             $product->setCoupon($randomString);            
             $em->persist($product);
             $em->flush();           
             $merchant=$em->getRepository('Model:Product_Detail_List')->findOneBy(['productId'=>$id]);
             $mid=$merchant->getMerchantId();             
        }catch(\Exception $exception){            
            echo " Error while generating coupon";
        }
        return  $this->redirectToRoute("list_products",array('id'=>$mid->getId())); 
    }
    //Function for updating products added
/**
     * @Route("/merchant/update",name="update");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
public function updateAction(Request $request)
{
        $form = $this->createForm(UpdateProductType::class);      
        $form->handleRequest($request); 
        $merchant=$this->getUser();
           try{               
                if ($form->isSubmitted() && $form->isValid()) 
                {     
                  $em = $this->getDoctrine()->getManager();
                  $product= new Product();
                  $product=$em->getRepository('Model:Product')->findOneBy(['id'=>'1']);
                  $descp=new Product_Description();
                  $descp=$em->getRepository('Model:Product_Description')->findOneBy(['id'=>'1']);     
                  //if new data entered then persist
                  if($product->getProductName()!= $name||$product->getProductPrice()!= $price||
                      $product->getDiscount()!= $discount||$descp->getColor()!=$color||$descp->getRamSize()!=$ram||
                      $descp->getCamera()!=$cam||$descp->getProductCompleteInfo()!=$info)
                     {                    
                      $descp->setColor($form->getData()["color"]);
                      $descp->setRamSize($form->getData()["ram_size"]);
                      $descp->setCamera($form->getData()["camera"]);
                      $descp->setProductCompleteInfo($form->getData()["product_complete_info"]);                    
                      $em->persist($descp);
                      $em->flush();                   
                
                       $product->setProductName($form->getData()["product_name"]);
                       $product->setProductDiscount($form->getData()["product_discount"]);
                       $product->setProductPrice($form->getData()["product_price"]);
                       $product->setBrandId($form->getData()["brandName"]);
                       $product->setCategoryId($form->getData()["categoryName"]);
                       $product->setProductDescriptionId($descp);                     
                       $em->persist($product);
                       $em->flush();
                  }
                  return $this->render("@Merchant/Default/updated.html.twig",array('product'=>$product,'descp'=>$descp,'merchant'=>$merchant));
               }
              
           }catch(\Exception $exception){
                  
         echo "Error while updating product detail";
         }
         return $this->render("@Merchant/Default/update.html.twig",array('form'=> $form->createView(),'merchant'=>$merchant)); 
    }
    //Function for displaying merchant info
    /**
     * @Route("/merchant/details",name="company_details");
     * @param Request $request
     * @Security("has_role('ROLE_MERCHANT')");
     */
    public function detailAction(Request $request)
    {
    try{        
        $merchant=$this->getUser();
        
    }catch(\Exception $exception){        
        echo "Error in Merchant Details";
    }
    return $this->render("@Merchant/Account/detail.html.twig",array('merchant'=> $merchant));
  }    
}

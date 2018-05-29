<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Common\Model\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use MerchantBundle\Form\AddProductType;
use Common\Model\Category;
use Common\Model\Brand;
use Common\Model\Product_Detail_List;
use Common\Model\Product_Description;


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
                $category = $form->getData()["category"];
                $brand = $form->getData()["brand"];
                $color = $form->getData()["color"];
                $ram = $form->getData()["ram_size"];
                $cam = $form->getData()["camera"];
                $info = $form->getData()["product_complete_info"];
                
                $image = $form->getData()["product_photo"];
                
                $pro= new Product();
                $pro->setProductName($name);
                $pro->setProductDiscount($discount);
                $pro->setProductPrice($price);
                
                $imageName =  $pro->getProductName(). '.' . $image->guessExtension();
               // $image->move($this->getParameter('product_image_directory'),$imageName);
                $pro->setPhotoName($imageName);
                
                $cat= new Category();
                $cat->setCategoryName($category);
                
                $brand1= new Brand();
                $brand1->setCategoryName($brand);
                
                $imei1= new Product_Detail_List();
                $imei1->setProductIMEI($imei);
                
                $descp = new Product_Description();
                $descp->setColor($color);
                $descp->setRamSize($ram);
                $descp->setCamera($cam);
                $descp->setProductCompleteInfo($info);
                
//                 $em->persist($pro);
//                 $em->flush(); 
//                 $em->persist($imei1);
//                 $em->flush();
//                 $em->persist($descp);
//                 $em->flush();
//                 $em->persist($brand1);
//                 $em->flush();
//                 $em->persist($cat);
//                 $em->flush();
           }
            return $this->render("@Merchant/Default/add.html.twig",array('form' => $form->createView()));
            
       }catch(\Exception $exception){
                
           var_dump($exception);
           die;
       
       }
       
  }
        
       
        
      

    
    /**
     * @Route("/list",name="list_products");
     * @param Request $Request
     */
    
    public function listProductAction(Request $Request){
        
        
        return $this->render("@Merchant/Default/list.html.twig");
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
  
}

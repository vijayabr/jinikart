<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Proxies\__CG__\Common\Model\Brand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductController extends Controller
{ 
    public function formBuilding(){
        $productsearch = $this->createFormBuilder()
        ->add('brand', EntityType::class, array('class' => 'Common\Model\Brand',
            'choice_label' => function ($state) {
             return $state->getBrandName();
             },'placeholder' => 'Select brand','required' => false))
            ->add('min', RangeType::class, array(
                'attr' => array(
                    'min' => 0,
                    'max' => 100000,
                    'value'=>0,
                    'required' => false       
                )
            ))
            ->add('max', RangeType::class, array(
                'attr' => array(
                    'min' => 0,
                    'max' => 100000,
                    'value'=>100000,
                    'required' => false
                )
            ))
            ->add('keyword',TextType::class,array('label'=>'Keyword','required' => false))
            ->getForm();
            return $productsearch;
    }
    
    /**
     * @Route("/customer/index",name="index_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function customerIndexAction(Request $request)
    {
        
        $customer = $this->getUser();
        $productsearch=$this->formBuilding();
        $productsearch->handleRequest($request);
        try{         
            if($productsearch->isSubmitted()){
                $max=$productsearch->getData()["max"];
                $min=$productsearch->getData()["min"];
                $brand=$productsearch->getData()["brand"];
                $keyword=$productsearch->getData()["keyword"];
              
                $em = $this->getDoctrine()->getManager();
                $merchant = $this->getDoctrine()->getRepository('Model:Merchant')->findAll();
                $brands = $this->getDoctrine()->getRepository('Model:Brand')->brandNameList();
                $categorys = $this->getDoctrine()->getRepository('Model:Category')->categoryNameList();
//               
                $productdescription1=$this->getDoctrine()->getRepository('Model:Product_Description')->findAll();
               
                //remove evrything and rewrite
                if($brand){
                    $products = $em->getRepository('Model:Product')->productsearchBasedonBrand($brand->getId(),$min,$max);
                    return $this->render("@Customer/Default/productList.html.twig",array('customer'=> $customer,'products'=> $products,'brand'=>$brands,'category'=>$categorys,'merchants'=>$merchant));
               }
               elseif($keyword)
               {
                   $products = $em->getRepository('Model:Product')->keywordsearch($keyword);
                  
                   return $this->render("@Customer/Default/productList.html.twig",array('customer'=> $customer,'products'=> $products,'brand'=>$brands,'category'=>$categorys,'merchants'=>$merchant));
               }
                else{
                    $products = $em->getRepository('Model:Product')->productsearch();
                    return $this->render("@Customer/Default/productList.html.twig",
                        array('customer'=> $customer,'products'=> $products,'brand'=>$brands,'category'=>$categorys,'merchants'=>$merchant));               
                } 
                //END
            }            
        }
        catch (\Exception $exception) {
             var_dump($exception);die;
         }
        return $this->render("@Customer/Default/homepage.html.twig",array('form' => $productsearch->createView(),'customer'=>$customer));      
      }
    
     
    /**
     * @Route("/customer/product/details/{pName}",name="productdetails_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    
    public function ProductDetailsAction($pName,Request $request)
    {
        try{
        $customer = $this->getUser();
        $em = $this->getDoctrine();
        $product = $em->getRepository('Model:Product')->findOneBy(['productName'=>$pName]);
        $brand = $this->getDoctrine()->getRepository('Model:Brand')->findAll();

        $category = $this->getDoctrine()->getRepository('Model:Category')->findAll();
        $productdescription=$this->getDoctrine()->getRepository('Model:Product_Description')->findAll();
     /*    dump($product,$brand);
        dump($product->getBrandId()->getbrandname());  */       
        return $this->render("@Customer/Default/productinfo.html.twig",array('customer' => $customer,'product'=>$product));
         }catch (\Exception $exception) {
            var_dump($exception->getMessage());die;
        }
    }
    
    
    /**
     * @Route("/customer/products",name="advancedproductList_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */       
    public function ProductAdvanceSearchAction(Request $request)
    {
        try{
        $customer = $this->getUser();
        $em = $this->getDoctrine();
        $sortedList=$request->request->get("sortlist");            
        $products = $em->getRepository('Model:Product_Detail_List')->productAdvancedSearch($sortedList); 
        $msg="<div class='col-sm-9 col-md-6 col-lg-8'id='productsList' style='padding:20px 20px 20px 20px;'>";
        if($products){
            foreach ($products as $product){             
            $name=$product->getProductId()->getproductName();
            $price=$product->getProductId()->getproductName();
            $submsg="<div class=' well' id='product' style='background-color: #ff9800;margin-Top:2em; margin-left:5em; padding:20px 20px 20px 20px' > ";
            $submsg=$submsg."<a href=".$this->generateUrl ( 'productdetails_page', ['pName'=> $name] ).">".$name."</a>";
            $submsg=$submsg.$price;
            $submsg=$submsg."</div>";
            $msg=$msg.$submsg;
            }
        }else{            
            $submsg="Currently No products available.";
            $msg=$msg.$submsg;
        }
        $msg=$msg."</div>";        
        return new Response($msg);
        }catch (\Exception $exception) {
            var_dump($exception->getMessage());die;
        }
    }   
}

<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Proxies\__CG__\Common\Model\Brand;

class ProductController extends Controller
{
    /**
     * @Route("/customer/index",name="index_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    
    public function customerIndexAction(Request $request)
    {
        
        $customer = $this->getUser();
     
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
                'value'=>1,    
                'required' => false
            )
        ))         
        ->getForm();
        $productsearch->handleRequest($request);
        try{         
            if($productsearch->isSubmitted()){

                $max=$productsearch->getData()["max"];
                $min=$productsearch->getData()["min"];
                $brand=$productsearch->getData()["brand"];
                $em = $this->getDoctrine()->getManager();
                $brands = $this->getDoctrine()->getRepository('Model:Brand')->brandNameList();
                $categorys = $this->getDoctrine()->getRepository('Model:Category')->categoryNameList();
                $productdescription1=$this->getDoctrine()->getRepository('Model:Product_Description')->findAll();
                
                if($brand){
                    $products = $em->getRepository('Model:Product')->productsearchBasedonBrand($brand,$min,$max);
                    
                    return $this->render("@Customer/Default/productList.html.twig",
                        array('customer'=> $customer,'products'=> $products,'brand'=>$brands,'category'=>$categorys));
                    
                }
                else{
                    $products = $em->getRepository('Model:Product')->productsearch($min,$max);
                    return $this->render("@Customer/Default/productList.html.twig",
                        array('customer'=> $customer,'products'=> $products,'brand'=>$brands,'category'=>$categorys));
                           
                }
                     
            }
            
        }
        catch (\Exception $exception) {
        var_dump($exception);
        die;
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
        $customer = $this->getUser();
        $em = $this->getDoctrine();
        $product = $em->getRepository('Model:Product')->findOneBy(['productName'=> $pName]);
        $brand = $this->getDoctrine()->getRepository('Model:Brand')->Brandinfo(1);
        $category = $this->getDoctrine()->getRepository('Model:Category')->Categoryinfo(1);
        $productdescription=$this->getDoctrine()->getRepository('Model:Product_Description')->findAll();
        dump($product,$brand);
        dump($product->getBrandId()->getbrandname());
        
        return $this->render("@Customer/Default/productinfo.html.twig",array('customer' => $customer,'product'=>$product));
        
    }
    
    /**
     * @Route("/customer/products/{sortlist}",name="advancedproductList_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    
    public function ProductAdvanceSearchAction($sortlist,Request $request)
    {
        $customer = $this->getUser();
        $em = $this->getDoctrine();
        $product = $em->getRepository('Model:Product')->findOneBy(['productName'=> $pName]);
        $brand = $this->getDoctrine()->getRepository('Model:Brand')->Brandinfo(1);
        $category = $this->getDoctrine()->getRepository('Model:Category')->Categoryinfo(1);
        $productdescription=$this->getDoctrine()->getRepository('Model:Product_Description')->findAll();
        dump($product,$brand);
        dump($product->getBrandId()->getbrandname());
        
        return $this->render("@Customer/Default/productinfo.html.twig",array('customer' => $customer,'product'=>$product));
        
    }
    
    
    
    
}

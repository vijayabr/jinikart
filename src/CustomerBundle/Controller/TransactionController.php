<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CustomerBundle\Form\AddCartType;

class TransactionController extends Controller
{
    /**
     * @Route("/customer/cart/{id}", name="add_cart");
     * @param Request $request
     */
    public function addCartAction(Request $request,$id)
    {
        $form = $this->createForm(AddCartType::class);
        $form->handleRequest($request);     
        
        try{
         $em=$this->getDoctrine()->getManager();
         $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
         
      /*    if($form->isSubmitted()){     
             $quantity=$form->getData()["product_count"];            
         }
         
       */   
       return $this->render("@Customer/Default/cart.html.twig",array('form'=>$form->createView(),'product'=>$product)); 
         
        }catch(\Exception $exception){
            
            return new Response($exception);
            die;
        }
        
    }
    
    /**
     * @Route("/customer/wish/{id}", name="wish_cart");
     * @param Request $request
     */
    public function addWishListAction(Request $request,$id)
    {
       
        try{
            
//             $em=$this->getDoctrine()->getManager();
//             $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
//             dump($product);die;
            
            return $this->render("@Customer/Default/wishList.html.twig");
            //             dump($product);die;
            
            //         if($form->isSubmitted()){
            
            //                 $quantity=$form->getData()["product_count"];
            //                 $product->setProductCount($count);
            
            
            //              }
            
            
        }catch(\Exception $exception){
            
            return new Response($exception);
            die;
        }
        
    }
    /**
     * @Route("/customer/order/{id}", name="place_order");
     * @param Request $request
     */
    public function placeOrderAction(Request $request,$id)
    {
        
        try{
            
            //             $em=$this->getDoctrine()->getManager();
            //             $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
            //             dump($product);die;
            
            return $this->render("@Customer/Default/placeOrder.html.twig");
            //             dump($product);die;
            
            //         if($form->isSubmitted()){
            
            //                 $quantity=$form->getData()["product_count"];
            //                 $product->setProductCount($count);
            
            
            //              }
            
            
        }catch(\Exception $exception){
            
            return new Response($exception);
            die;
        }
        
    }
}

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
            //return new Response("Hii");
         //   $product=$request->request->get('productName');;
         $em=$this->getDoctrine()->getManager();
         $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
         
         return $this->render("@Customer/Default/cart.html.twig",array('form'=>$form->createView(),'product'=>$product));
//             dump($product);die;
            
//             if($form->isSubmitted()){
                
//                 $quantity=$form->getData()["product_count"];
//                 $product->setProductCount($count);
                
                
//             }
            
         
        }catch(\Exception $exception){
            
            return new Response($exception);
            die;
        }
        
    }
}

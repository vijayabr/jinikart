<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CustomerBundle\Form\AddCartType;
use Common\Model\Cart;
use Common\Model\CartList;
use Common\Model\ProductOrder;
use Common\Model\ProductOrderDetail;

class TransactionController extends Controller
{
    /**
     * @Route("/customer/cart/{cid}/{id}", name="add_cart");
     * @param Request $request
     */
    public function addCartAction(Request $request,$cid,$id)
    {
        $form = $this->createForm(AddCartType::class);
        $form->handleRequest($request);     
       
        try{
          $em=$this->getDoctrine()->getManager();
          $product=$em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
          
          if($form->isSubmitted()){     
              $cart= new Cart();       
              $cart->setCartStatus(1);
              $cart->setCustomerId($id);
            //  dump($cart);die;
            //  $em->persist($cart);
            //  $em->flush();
              
              $cartlist= new CartList();
              $em=$this->getDoctrine()->getManager();
              $productimei=$em->getRepository('Model:Product')->findIMEI(['id'=>$id]);            
              $cartlist->setProductIMEI($productimei);
              $cartlist->setCartId($cart);
            //   dump($cartlist);die;
            //  $em->persist($cartlist);
            //  $em->flush();
              $productOrder= new ProductOrder();
              $productOrder->setOrderedDate(new \DateTime());
              $productOrder->setOrderStatus(1);
              $productOrder->setCustomerId($cid);
            
              //   dump(productOrder);die;
              //  $em->persist($productOrder);
              //  $em->flush();
              
              $productOrderDetail= new ProductOrderDetail();
              $productOrderDetail->setDeliveryDate(new \DateTime());
              $productOrderDetail->setProductOrderIdId($productOrder);
              $productOrderDetail->setCartListId($cartlist);
              //dump($productOrderDetail);die;
              //  $em->persist($productOrder);
              //  $em->flush();
              
              }
              
         
         
         
       return $this->render("@Customer/Default/cart.html.twig",array('form'=>$form->createView(),'product'=>$product)); 
         
        } catch(\Exception $exception){
            
            return new Response($exception);
            die;
        }
         
    }
    
    /**
     * @Route("/customer/wish/{cid}/{id}", name="wish_cart");
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
            
            $em=$this->getDoctrine()->getManager();
            $product=$em->getRepository('Model:Product')->findProductDetails(['id'=>$id]);
          // dump($product);die;
            return $this->render("@Customer/Default/placeOrder.html.twig",array('product'=>$product));
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

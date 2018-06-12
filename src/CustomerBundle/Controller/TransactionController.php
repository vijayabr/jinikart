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
use Common\Model\Product_Detail_List;
use Doctrine\DBAL\Types\BigIntType;

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
            $customerId=$em->getRepository('Model:Customer')->findOneBy(['id'=>$cid]);
           
            
           if($product){
               //  dump($product->getId());die; 
            $cart= new Cart();
            $cart->setCartStatus(Cart::FULL); //OCCUPIED
            $cart->setCustomerId($customerId);
           // dump($cart);
            $em->persist($cart);
            $em->flush();
            
          //  $proid=$product->getId(); 
            $productimei=new Product_Detail_List();
            
            $productimei=$em->getRepository('Model:Product_Detail_List')->findIMEI(['id'=>$product->getId()]); 
          // dump($productimei);die;
            
            $cartlist= new CartList();
            $cartlist->setProductIMEI((bigint)($productimei['0']->getProductIMEI()));
            $cartlist->setCartId($cart);
           // dump($cartlist);die;
            $em->persist($cartlist);
            $em->flush();
           
          //  return $this->render("@Customer/Default/placeOrder.html.twig",array('product'=>$product));
          }
            
       
       return $this->render("@Customer/Default/cart.html.twig",array('form'=>$form->createView(),'product'=>$product,'cid'=>$cid)); 
         
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
     * @Route("/customer/order/{cid}/{id}", name="place_order");
     * @param Request $request
     */
    public function placeOrderAction(Request $request,$cid,$id)
    {
         $em=$this->getDoctrine()->getManager();
        //    $product=$em->getRepository('Model:Product')->findProductDetails(['id'=>$id]);
          // dump($product);die;
       //     return $this->render("@Customer/Default/placeOrder.html.twig",array('product'=>$product));
            //             dump($product);die;
         $productOrder= new ProductOrder();
         $productOrder->setOrderedDate(new \DateTime());
         $productOrder->setOrderStatus(1);
         $productOrder->setCustomerId($cid);
                     
        // dump($productOrder);
         //  $em->persist($productOrder);
         //  $em->flush();
                 
         $cartlist= new CartList();
         $cartlist=$em->getRepository('Model:Cart')->findCartListId(['id'=>$cid]);  //write query
                     
         $productOrderDetail= new ProductOrderDetail();
         $productOrderDetail->setDeliveryDate(new \DateTime());
         $productOrderDetail->setProductOrderIdId($productOrder);
         $productOrderDetail->setCartListId($cartlist);
        // dump($productOrderDetail);
         //  $em->persist($productOrder);
         //  $em->flush();       
        return $this->render("@Customer/Default/placeOrder.html.twig");
             
    }
}

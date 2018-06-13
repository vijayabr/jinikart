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
//use Common\Model\Product_Detail_List;
//use Doctrine\DBAL\Types\BigIntType;
//use CustomerBundle\Form\OrderType;
// use Ivory\GoogleMap\Service\Place\Autocomplete\PlaceAutocompleteService;
// use Ivory\GoogleMap\Service\Serializer\SerializerBuilder;
//use Http\Adapter\Guzzle6\Client;
//use Http\Message\MessageFactory\GuzzleMessageFactory;
//use Ivory\GoogleMap\Service\Place\Autocomplete\Request\PlaceAutocompleteRequest;


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
              
            $cart= new Cart();
            $cart->setCartStatus(Cart::FULL); //OCCUPIED
            $cart->setCustomerId($customerId);
            $em->persist($cart);
            $em->flush();
            
          //  $productimei=$em->getRepository('Model:Product_Detail_List')->findIMEI(['id'=>$product->getId()]);
           // $imei=$em->getRepository('Model:Product_Detail_List')->findIMEI(['id'=>$product->getId()]);
          //  dump($productimei);die;
            
            $cartlist= new CartList();
         // $cartlist->setProductImeiId();   //Error
            $cartlist->setCartId($cart);
            //dump($cartlist);die;
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
       
//         try{
            
//             if($form->isSubmitted()){
            
//             $quantity=$form->getData()["product_count"];
//             $product->setProductCount($count);
            
//           }
//             return $this->render("@Customer/Default/wishList.html.twig");
       
//         }catch(\Exception $exception){
            
//             return new Response($exception);
//             die;
//         }
        
    }
    /**
     * @Route("/customer/order/{cid}/{id}", name="place_order");
     * @param Request $request
     */
    public function placeOrderAction(Request $request,$cid,$id)
    {
//         $form=$this->createForm(OrderType::class);
//         $form->handleRequest($request);
        
//         $autocomplete = new PlaceAutocompleteService(
//             new Client(),
//             new GuzzleMessageFactory(),
//             SerializerBuilder::create($psr6Pool)
//             );
//        $response = $autocomplete->process(new PlaceAutocompleteRequest('Sydney'));
        
        
//         $request = new PlaceAutocompleteRequest('Sydney');
//         $response = $this->container->get('ivory.google_map.place_autocomplete')->process($request);
        
        try{
            $em=$this->getDoctrine()->getManager();
            $customerId=$em->getRepository('Model:Customer')->findOneBy(['id'=>$cid]);
            $productOrder= new ProductOrder();
            $productOrder->setOrderedDate(new \DateTime());
            $productOrder->setOrderStatus(ProductOrder::Order_Placed);
            $productOrder->setCustomerId($customerId);
            
            $em->persist($productOrder);
            $em->flush();
            //  dump($productOrder);
            //  dump($customerId->getId());die;
            //  $cartlist= new CartList();
            //  $cartlist=$em->getRepository('Model:CartList')-> findCartListId();  //write query
            
            $productOrderDetail= new ProductOrderDetail();
            $productOrderDetail->setProductOrderId($productOrder);
            //  $productOrderDetail->setCartListId($cartlist);  //Error
            // dump($productOrderDetail);
            $em->persist($productOrderDetail);
            $em->flush();
//             if(){
//                 $productOrderDetail->setDeliveryDate(new \DateTime());
//                 $em->persist($productOrderDetail);
//                 $em->flush();
//             }
          
           
          return $this->render("@Customer/Default/placeOrder.html.twig");
      }catch(\Exception $exception){
            
            return new Response($exception);
            die;
       }
    }
}

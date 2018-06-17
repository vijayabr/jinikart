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
use Ivory\GoogleMap\Service\Place\Autocomplete\PlaceAutocompleteService;
use Ivory\GoogleMap\Service\Serializer\SerializerBuilder;
use Ivory\GoogleMap\Service\Place\Autocomplete\Request\PlaceAutocompleteRequest;
use Common\Model\WishList;
use Symfony\Component\Validator\Constraints\Length;
use Ivory\GoogleMap\Service\Place\Autocomplete\Response\PlaceAutocompleteResponse;
use Ivory\GoogleMap\Place\Autocomplete;
use Ivory\GoogleMap\Helper\Builder\PlaceAutocompleteHelperBuilder;
use Ivory\GoogleMap\Helper\Builder\ApiHelperBuilder;


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
            $cart=$em->getRepository('Model:Cart')->findOneBy(['customerId'=>$cid]);
             
            if($cart!=null)
            {
            $cart->setCartStatus(Cart::FULL); //OCCUPIED
            $em->persist($cart);
            $em->flush();
            }
            else{
                $cart= new Cart();
                
                $cart->setCustomerId($customerId);
                $cart->setCartStatus(Cart::FULL);
              
                $em->persist($cart);
                $em->flush();
            }
            $imei=new Product_Detail_List();
            $imei=$em->getRepository('Model:Product_Detail_List')->findOneBy(['productId'=>$product->getId()]);
        
      
            $cartlist= new CartList();
            $cartlist->setProductImeiId($imei); 
            $cartlist->setCartId($cart);
            $em->persist($cartlist);
            $em->flush();
           
            $count= $em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
           
            $wishlist= new WishList();
            if($count->getProductCount()){
                $wishlist->setWishlistStatus(Wishlist::IN_STOCK);
                $wishlist->setProductId($product);
                $wishlist->setCustomerId($customerId);
              
              $em->persist($wishlist);
              $em->flush();
             }
             else
             {
                $wishlist->setWishlistStatus(Wishlist::OUT_OF_STOCK);
                
             }
            
           } 
          // dump($product);die;
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
    public function addWishListAction(Request $request,$cid,$id)
    {
       
         try{
             $em=$this->getDoctrine()->getManager();
            $product= $em->getRepository('Model:Product')->findOneBy(['id'=>$id]);
            $wish=$em->getrepository('Model:WishList')->findBy(['customerId'=>$cid]);
            return $this->render("@Customer/Default/wishList.html.twig",array('product'=>$product,'wish'=>$wish));
       
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
        return $this->render("@Customer/Default/placeOrder.html.twig");
        
//         $form=$this->createForm(OrderType::class);
//         $form->handleRequest($request);
        $autocomplete = new Autocomplete();
        $autocomplete->setVariable('place_autocomplete');
        //dump($autocomplete);die;
        
        $placeAutocompleteHelperBuilder = PlaceAutocompleteHelperBuilder::create();
        $placeAutocompleteHelper = $placeAutocompleteHelperBuilder->build();
       // dump($placeAutocompleteHelper);die;
       echo  $placeAutocompleteHelper->renderHtml($autocomplete);
       echo  $placeAutocompleteHelper->renderJavascript($autocomplete);
        
        $apiHelper = ApiHelperBuilder::create()->build();
        echo    $apiHelper->render([$autocomplete]);
        try{
            $em=$this->getDoctrine()->getManager();
            $customerId=$em->getRepository('Model:Customer')->findOneBy(['id'=>$cid]);
            $productOrder= new ProductOrder();
            $productOrder->setOrderStatus("Requested");
            $productOrder->setOrderedDate(new \DateTime());
            $productOrder->setCustomerId($customerId);             
            $em->persist($productOrder);
            $em->flush();
           
             $cart= new Cart();
             $cartlist= new CartList();
             
             $cart=$em->getRepository('Model:Cart')->findBy(['customerId'=>$cid]);
             $cartlist=$em->getRepository('Model:CartList')-> findOneBy(['cartId'=>$cart]);
                     
             $productOrderDetail= new ProductOrderDetail();
             $productOrderDetail->setProductOrderId($productOrder);
             $productOrderDetail->setCartListId($cartlist);  
             $productOrderDetail->setDeliveryDate(new \DateTime());
             $em->persist($productOrderDetail);
             $em->flush();
        
           }catch(\Exception $exception){
            
            return new Response($exception);
            die;
       }
    }
    /**
     * @Route("/customer/delete/{cid}/{id}", name="delete_item");
     * @param Request $request
     */
    public function deleteAction(Request $request,$cid,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $cartId=$em->getRepository('Model:Cart')->findBy($cid);
        dump($cartId);
        $item=$em->getRepository('Model:CartList')->findBy($cartId);
        dump($item);
        $em->remove($item);
        $em->flush();
        $cart= new Cart();
        $cart= $em->getRepository('Model:Cart')->findBy($cartId);
        $cart->setCartStatus(Cart::DEFAULT_CART_STATUS);
        $wish=$em->getRepository('Model:WishList')->findBy($id);
        $em->remove($wish);
        $em->flush();
        return $this->redirectToRoute("add_cart");
    }
}

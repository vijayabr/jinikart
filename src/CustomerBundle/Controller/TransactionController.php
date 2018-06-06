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
     * @Route("/customer/cart", name="add_cart");
     * @param Request $request
     */
    public function ProductAdvanceSearchAction(Request $request)
    {
        $form = $this->createForm(AddCartType::class);
        $form->handleRequest($request);     
        try{
            return new Response("Hii");
            
            //return $this->render("@Customer/Default/cart.html.twig");
        }catch(\Exception $exception){
            
            return new Response($exception);
            die;
        }
        
    }
}

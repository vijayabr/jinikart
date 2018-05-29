<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        dump($customer);die;
        return $this->render("@Customer/Default/homepage.html.twig",['customer'=>$customer]);
        
        
    }
    
}

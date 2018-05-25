<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductManagementController extends Controller
{
    /**
     * @Route("/merchant/Add",name="add_products");
     * @param Request $Request
     */
    
    public function addProductAction(Request $Request){
        
        
    }
    
    /**
     * @Route("/merchant/List",name="list_products");
     * @param Request $Request
     */
    
    public function listProductAction(Request $Request){
        
        
    }
}

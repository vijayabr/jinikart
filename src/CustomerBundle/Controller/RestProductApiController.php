<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;


class RestProductApiController extends FOSRestController
{
    /**
     * @Rest\Get("api/product_display")
     *
     */
    public function ProductDisplayAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$products = $em->getRepository('Model:Product')->productsearch();
    	$products['status_code'] = "OK";
    	return $products;
    }

    /**
     * @Rest\Get("api/product_display/{id}", name="blog_list", requirements={"page"="\d+"})
     *
     */
    public function ProductDisplayByIdAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$products = $em->getRepository('Model:Product')->findAllProductDetails(1);
    	dump($products);die;
    	$products['status_code'] = "OK";
    	return $products;
    }

}

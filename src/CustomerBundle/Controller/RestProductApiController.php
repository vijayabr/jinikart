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
     * @Rest\Get("api/product_display/{id}", name="display_product", requirements={"page"="\d+"})
     *
     */
    public function ProductDisplayByIdAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$products = $em->getRepository('Model:Product')->findProductById($id);

    	$products['status_code'] = "OK";
    	return $products;
    }

    /**
     * @Rest\Put("api/product_display/{id}", name="update_product", requirements={"page"="\d+"})
     *
     */
    public function ProductUpdateByIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('Model:Product')->find($id);
        if (!$products) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
        }

        $products->setProductName('nokia n96');
        $em->flush();

        return "{'status code': 'ok', 'query':'executed'}";
    }

}

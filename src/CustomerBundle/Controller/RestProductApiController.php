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
    	$myArr = array("name"=>"rolwin", "d"=>"a");
        return $myArr;
    }

}

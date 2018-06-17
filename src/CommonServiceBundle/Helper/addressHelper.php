<?php 
namespace CommonServiceBundle\Helper;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Common\Model\Address;
use Symfony\Component\HttpFoundation\Response;


class addressHelper{
   
    public  $container;
  
    
    public function __construct(Container $container){
        $this->container=$container;             
    }
    
    public function setAddress($form){
        try{ 
            $em = $this->container->get('doctrine')->getEntityManager();
            $validator=$this->container->get('validator');
            $address =new Address();
            $address->setAddressLine1($form["address_line1"]);
            $address->setAddressLine2($form["address_line2"]);
            $address->setStateId($form["state"]);
            $address->setCountryId($form["country"]);            
            $address->setPincode($form["pincode"]); 
            $error1=$validator->validate($address);
            if(!count($error1)){
                $em->persist($address);
                //$em->flush();
                return $address;
            }else{
                 return $error1;
                
            }
           
        }
        catch (Exception $e){
            return new Response("Errors are found");

        }
    }
        
}
<?php 
namespace CustomerBundle\Helper;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Common\Model\Address;
use Symfony\Component\HttpFoundation\Response;
use Common\Model\Customer;


class customerDataSetHelper{
   
    public  $container;
        
    public function __construct(Container $container){
        $this->container=$container;             
    }
    
    public function setCustomerObject($form,$address,$customerPlan,$imageName){
        try{ 
            $em = $this->container->get('doctrine')->getEntityManager();
            $validator=$this->container->get('validator');
            $customer= new Customer();
            $customer->setFname($form["fname"]);
            $customer->setLname($form["lname"]);
            $customer->setEmail($form["email"]);
            $customer->setMobileNo($form["mobile_no"]);
            $customer->setPassword($form["password"]);
            $customer->setCustomerPlanId($customerPlan);
            $customer->setCustomerStatus(Customer::ACTIVE);
            $customer->setCustomerRole(Customer::ROLE);
            $customer->setAddressId($address);
            if($imageName){
                $customer->setProfilePhoto($imageName);
            }
            $customer->setPassword($this->container->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form['password'], ''));
            $error1 =$validator->validate($customer); 
            if(!count($error1)){
                $em->persist($customer);
                $em->flush();
                return $customer;
            }else{
                 return $error1;
                
            }
           
        }
        catch (Exception $e){
            return new Response("Errors are found");

        }
    }
    
        
}
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
            $customer->setFname($form->getData()["fname"]);
            $customer->setLname($form->getData()["lname"]);
            $customer->setEmail($form->getData()["email"]);
            $customer->setMobileNo($form->getData()["mobile_no"]);
            $customer->setPassword($form->getData()["password"]);
            $customer->setCustomerPlanId($customerPlan);
            $customer->setCustomerStatus(Customer::ACTIVE);
            $customer->setCustomerRole(Customer::ROLE);
            $customer->setAddressId($address);
            $customer->setProfilePhoto($imageName);
            $customer->setPassword($this->container->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form->getData()['password'], ''));
            $error1 =$validator->validate($customer); 
            if(!count($error1)){
                $em->persist($customer);
               // $em->flush();
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
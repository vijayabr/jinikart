<?php 
namespace MerchantBundleBundle\Helper;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Common\Model\Address;
use Symfony\Component\HttpFoundation\Response;
use Common\Model\Customer;
use Common\Model\Merchant;


class merchantDataSetHelper{
   
    public  $container;
        
    public function __construct(Container $container){
        $this->container=$container;             
    }
    
    public function setMerchantObject($form,$address,$merchantPlan,$imageName){
        try{ 
            $em = $this->container->get('doctrine')->getEntityManager();
            $validator=$this->container->get('validator');
            $merchant = new Merchant();
            $merchant->setCompanyName($form["companyName"]);
            $merchant->setcontactPersonName($form["contactPersonName"]);
            $merchant->setEmail($form["email"]);
            $merchant->setMobileNo($form["mobileNo"]);
            $merchant->setPassword($form["password"]);
            $merchant->setmerchantPlanId($merchantPlan);
            $merchant->setMerchantStatus(Merchant::ACTIVE);
            $merchant->setMerchantRole(Merchant::ROLE);
            $merchant->setCompanyLogo($imageName);
            $merchant->setAddressId($address);
            if($imageName){
                $merchant->setCompanyLogo($imageName);
            }
                $merchant->setPassword($this->container->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form['password'], ''));
            $error =$validator->validate($merchant); 
            if(!count($error)){
                $em->persist($merchant);
                $em->flush();
                return $merchant;
            }else{
                 return $error;                
            }
           
        }
        catch (Exception $e){
            return new Response("Errors are found");

        }
    }
    
        
}
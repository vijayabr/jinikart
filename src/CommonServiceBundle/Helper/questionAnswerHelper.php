<?php 
namespace CommonServiceBundle\Helper;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Common\Model\SecretAnswer;
use Common\Model\Customer;


class questionAnswerHelper{
   
    public  $container;
  
    
    public function __construct(Container $container){
        $this->container=$container;             
    }
    
    public function setQuestionAnswer($form,$question){
        try{ 
            $em = $this->container->get('doctrine')->getEntityManager();
            $questionAnser= new SecretAnswer();
            $questionAnser->setAnswer($form['question1']);
            $em = $this->getDoctrine()->getManager();
            $questionAnser->setQuestionId($q1);
            $questionAnser->setRole(Customer::ROLE);
            $questionAnser->setRoleId($customer);
            $em->persist($questionAnser);
            $em->flush();           
        }
        catch (Exception $e){
            return new Response("Errors are found");

        }
    }
        
}
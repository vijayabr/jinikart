<?php

namespace CommonServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CommonServiceBundle\Service\MessageGenerator;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
//     private $messageGenerator;
//     private $mailer;
    
//     public function __construct(MessageGenerator $messageGenerator, \Swift_Mailer $mailer)
//     {
//         $this->messageGenerator = $messageGenerator;
//         $this->mailer = $mailer;
//     }
    
    /**
     * @Route("/index")
     */
    public function newAction()   {
        
        try{
            $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
             ->setFrom('jinikartecommerce@gmail.com')
            ->setTo('aishwarya.mk@techjini.com')
            ->setBody("Hello");
            $response = $this->get('mailer')->send($message);
            return new Response("success");           
        }catch (\Exception $e){
            dump($e);die;
        }
      
    }
}

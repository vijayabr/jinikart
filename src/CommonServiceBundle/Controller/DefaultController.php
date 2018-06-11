<?php

namespace CommonServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
    public function mailSending($mailer,$to,$body,$subject,$filePath=null){
                
        if($filePath){
        $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('jinikartecommerce@gmail.com')
        ->setTo($to)
        ->setBody($body)
        ->attach(\Swift_Attachment::fromPath($filePath));
        }
        else{
            $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('jinikartecommerce@gmail.com')
            ->setTo($to)
            ->setBody($body);
                      
        }
        $response = $mailer->send($message); 
        return $response;
        
     }
    /**
     * @Route("/index")
     */
    public function newAction()   {
        
        try{
            $mailer = $this->get('mailer');
            $directory=$this->getParameter('product_file_directory');
            $filePath=$directory."//Techjini.pdf";
            $from="jinikartecommerce@gmail.com";
            $to='jinikartecommerce@gmail.com';
            $body="Hii\nPlease find the attached file \n Thank you";
            $subject='Hello Email';
            $a=$this->mailSending($mailer,$to, $body,$subject);
            
            return new Response("success");           
        }catch (\Exception $e){
            dump($e);die;
        }
      
    }
    
    
   
        
}

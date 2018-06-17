<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Bridge\Doctrine\Security\RememberMe;

class SecurityController extends Controller
{
    /**
     * @Route("/customer/login", name="login");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
         try {
            $authenticationUtils=$this->get('security.authentication_utils');
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();    
            return $this->render('@Customer/Account/login.html.twig',array(
                'last_username' => $lastUsername,
                'error'=> $error,
            ));
        }        
        catch(Exception $exception){
            echo "Error Occurred While logging in";
        }
    }
    
    /**
     * @Route("/customer/logout", name="logout");
     */
    public function logoutAction()
    {
    }
    

}

<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;

use Symfony\Component\HttpFoundation\Cookie; 
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class SecurityController extends Controller
{
     /**
      * @Route ("/merchant/login",name="merchant_login");
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\Response
      * 
      */
    public function merchantLoginAction(Request $request)
    { 
       
        try {          
            $authenticationUtils=$this->get('security.authentication_utils');
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
          
            return $this->render('@Merchant/Account/login.html.twig',array(
                'last_username' => $lastUsername,
                'error'=> $error));   
            }
         catch(\Exception $exception){
            echo "Error while logging in";
        }
    }
    
    /**
     * @Route("/merchant/logout", name="merchant_logout");
     */
    public function logoutAction() {       
        return $this->redirectToRoute('merchant_login'); 
    }

}


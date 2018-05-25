<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends Controller
{
     /**
      * @Route ("/merchant/login",name="merchant_login");
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\Response
      */
    public function merchantLoginAction(Request $request)
    {
         try {

            $authenticationUtils=$this->get('security.authentication_utils');

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            //dump($this->get("request_stack"));die;

            return $this->render('@Merchant/Account/login.html.twig',array(
                'last_username' => $lastUsername,
                'error'=> $error,
            ));


        }
        catch(\Exception $exception){
            var_dump($exception);die;
        }
    }
    
    /**
     * @Route("/merchant/logout", name="merchant_logout");
     */
    public function logoutAction()
    {
        return $this->redirectToRoute('merchant_login'); 
    }

   

}


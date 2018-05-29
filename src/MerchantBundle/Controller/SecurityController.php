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
// @Security("IS_AUTHENTICATED_REMEMBERED");

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
//             $session = new Session();
//             // $session->get('name');
//             if(IsGranted("IS_AUTHENTICATED_REMEMBERED")){
//                 $session->invalidate(604800);
//             }
            
           // $request = Request::createFromGlobals();
            
          
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
           
         /*   $securityContext = $this->container->get('security.context');
            $user = $securityContext->getToken()->getUser();
            if (is_object($user) && $user instanceof UserInterface ) {
                return $this->redirect($this->generateUrl('merchant_login'));
            }else if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
                return $this->redirect($this->generateUrl('@Merchant/Default/homepage.html.twig'));
            }else{
                return $this->render('@Merchant/Default/landing.html.twig');
            }*/
         
            
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


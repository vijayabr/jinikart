<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;

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

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            //dump($this->get("request_stack"));die;

            return $this->render('@Customer/Account/login.html.twig',array(
                'last_username' => $lastUsername,
                'error'=> $error,
            ));


        }        
        catch(Exception $exception){
            var_dump($exception);die;
        }
    }
    /**
     * @Route("/customer/logout", name="logout");
     */

    public function logoutAction()
    {

    }

}

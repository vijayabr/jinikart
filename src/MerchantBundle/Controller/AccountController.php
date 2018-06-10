<?php

namespace MerchantBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MerchantBundle\Form\MerchantType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Common\Model\Address;
use Common\Model\Merchant;
use Common\Model\Merchant_plan;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @Route("/index")
     */
    public function newAction()   {
        $from='aishwaryamk96@gmail.com';
        $to='aishwaryamk96@gmail.com';
        $subject = "Subject";
        $header = "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";
        $emailContent="abcd123";
        $mailer = $this->container->get('mailer');
    
        $message = \Swift_Message::newInstance ()
        
        ->setSubject ( $subject )
        ->setFrom ( $from )
        ->setTo ( $to )
        ->setBody ( $emailContent )
        ->setContentType ( "text/html" );
        $reponse=$mailer->send ( $message );
        dump($reponse);die;
        return new Response("success");
    }
    
    /**
     * @Route("/merchant/registration", name="merchant_registration");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function merchantRegistrationAction(Request $request)
    {
        
        
        $form = $this->createForm(MerchantType::class);
        $form->handleRequest($request);
        $validator=$this->get('validator');
        
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $merchantPlan = $em->getRepository('Model:Merchant_plan')->findOneBy(['id' =>Merchant_plan::DEFAULT_MERCHANT_PLAN]);
                //get address
                $name=$form->getData()["companyName"];                
                $addr1 = $form->getData()["address_line1"];
                $addr2 = $form->getData()["address_line2"];
                $pin = $form->getData()["pincode"];
                $state = $form->getData()["state"];
                $country = $form->getData()["country"];
                $merchantMobileNoExist =$em->getRepository('Model:Merchant')->findOneBy(['mobileNo'=>$form->getData()["mobileNo"]]);
                $merchantEmailExist =$em->getRepository('Model:Merchant')->findOneBy(['email'=>$form->getData()["email"]]);
                $address = new Address();
                $address->setAddressLine1($addr1);
                $address->setAddressLine2($addr2);
                $address->setStateId($state);
                $address->setCountryId($country);
                $address->setPincode($pin);
                $error1=$validator->validate($address);
              
                if(!$error1){
                $em->persist($address);
                $em->flush();
                }
                $merchantMobileNoExist =$em->getRepository('Model:Merchant')->findOneBy(['mobileNo'=>$form->getData()["mobileNo"]]);
                $merchantEmailExist =$em->getRepository('Model:Merchant')->findOneBy(['email'=>$form->getData()["email"]]);
                
                if(!$merchantEmailExist && !$merchantMobileNoExist) {
                    
                    $merchant = new Merchant();
               
                    $address = new Address();
                    $address->setAddressLine1($addr1);
                    $address->setAddressLine2($addr2);
                    $address->setStateId($state);
                    $address->setCountryId($country);
                    $address->setPincode($pin);
                    $error1=$validator->validate($address);
                    if(!$error1){
                        $em->persist($address);
                        $em->flush();
                    }
                 
                    $merchant->setCompanyName($form->getData()["companyName"]);
                    $merchant->setcontactPersonName($form->getData()["contactPersonName"]);
                    $merchant->setEmail($form->getData()["email"]);
                    $merchant->setMobileNo($form->getData()["mobileNo"]);
                    $merchant->setPassword($form->getData()["password"]);
                    $merchant->setAddressId($address);
                    $merchant->setmerchantPlanId($merchantPlan);
                    $merchant->setMerchantStatus(Merchant::ACTIVE);
                    $merchant->setMerchantRole(Merchant::ROLE);
                    $merchant->setPassword($this->get('security.encoder_factory.generic')->getEncoder($merchant)->encodePassword($form->getData()['password'], ''));
                    
                    /**
                     *uplodedFile images
                     */
                   
                    $image = $form->getData()["companylogo"];
                    if($image){
                        $imageName =  $merchant->getcontactPersonName(). '.' . $image->guessExtension();
                        $image->move($this->getParameter('company_image_directory'),$imageName);
                        $merchant->setCompanyLogo($imageName);
                  
                    }
                    $errors =$validator->validate($merchant);
     
                   if(count($errors) > 0 || count($error1)>0){
                        return $this->render("@Merchant/Account/register.html.twig", array('form' => $form->createView(), 'message'=> '','errors'=>$errors, 'error1'=>$error1));
                    }
                   else {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($merchant);
                        $entityManager->flush();
                  
                        return $this->redirectToRoute('merchant_login'); 
                    }
                }
                else
                {
                    
                    $infomessage="you already have an account!!!";
                    return $this->render("@Merchant/Account/register.html.twig", array('form' => $form->createView(),'message'=> $infomessage,'errors'=>'', 'error1'=>'' ));
                }
            }
                   
                    return $this->render("@Merchant/Account/register.html.twig", array('form' => $form->createView(),'message'=> '','errors'=>'', 'error1'=>''));
          
        } catch (\Exception $exception) {
            return new  Response($exception);
            die;
    }
    
 }
    /**
     * @Route("/merchant/index", name="merchant_index");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function merchantIndexAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        
        $merchant = $this->getUser();
      
        return $this->render("@Merchant/Default/homepage.html.twig",array('merchant'=>$merchant));
        
    }
    /**
     * @Route("/merchant",name="merchant_landing");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function merchantLandingAction(Request $request){
       
        
        return $this->render("@Merchant/Default/landing.html.twig");
    }
    
    
}
        


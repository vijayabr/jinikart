<?php

namespace CustomerBundle\Controller;

use Common\Model\Address;
use Common\Model\Customer;
use CustomerBundle\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Config\Definition\Exception\Exception;
use Common\Model\Customer_plan;
use Common\Model\SecretAnswer;
use CustomerBundle\Form\ChangePasswordType;
use CustomerBundle\Form\ProfileType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;


class AccountController extends Controller 
{
    
    /**
     * @Route("/customer/registration",name="customer_registration");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
   
    public function customerRegistrationAction(Request $request)
    {

        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);
        $validator=$this->get('validator');
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $customerPlan = $em->getRepository('Model:Customer_plan')->findOneBy(['id' => Customer_plan::DEFAULTPLAN]);
                //get address
                $addr1 = $form->getData()["address_line1"];
                $pin = $form->getData()["pincode"];
                $state = $form->getData()["state"];
                $addr2 = $form->getData()["address_line2"];
                $country = $form->getData()["country"];
                $address = new Address();
                $address->setAddressLine1($addr1);
                $address->setAddressLine2($addr2);
                $address->setStateId($state);
                $address->setCountryId($country);
                $address->setPincode($pin);
                $error1=$validator->validate($address);
                $em->persist($address);
                $em->flush();
                $customerMobileNoExist =$em->getRepository('Model:Customer')->findOneBy(['mobileNo'=>$form->getData()["mobile_no"]]);
                $customerEmailExist =$em->getRepository('Model:Customer')->findOneBy(['email'=>$form->getData()["email"]]);
                if(!$customerEmailExist && !$customerMobileNoExist) {

                    $customer = new Customer();
                    $customer->setFname($form->getData()["fname"]);
                    $customer->setLname($form->getData()["lname"]);
                    $customer->setEmail($form->getData()["email"]);
                    $customer->setMobileNo($form->getData()["mobile_no"]);
                    $customer->setPassword($form->getData()["password"]);
                    $customer->setAddressId($address);
                    $customer->setCustomerPlanId($customerPlan);
               
                    $customer->setPassword($this->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form->getData()['password'], ''));
//                       dump($this->container);die;
                    /**
                     * @var uplodedFile images
                     */
                    $image = $form->getData()["profile_photo"];
                    $imageName = $customer->getFname() . $customer->getLname() . '.' . $image->guessExtension();

                    $image->move($this->getParameter('image_directory'),$imageName);
                    $customer->setProfilePhoto($imageName);
                    $errors =$validator->validate($customer);
                    if(count($errors) > 0 || count($error1)>0){
                        return $this->render("@Customer/Account/register.html.twig", array( 'form' => $form->createView(), 'message'=> '','errors'=>$errors, 'error1'=>$error1 ));
                    }
                    else {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($customer);
                        $entityManager->flush();
                        return $this->redirectToRoute("login");
                    }
                }
                else
                {

                    $infomessage="you already have an account!!!";
                    return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> $infomessage,'errors'=>'', 'error1'=>'' ));
                }

            }
            return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> '','errors'=>'', 'error1'=>'' ));
        } 
        catch (Exception $exception) {
            var_dump($exception);
            die;
        }

    }

   /**
    * @Route("/customer/index",name="index_page");
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response
    */
    

    public function customerIndexAction(Request $request)
    {
   
            $customer = $this->getUser();
            return $this->render("@Customer/Default/homepage.html.twig",['customer'=>$customer]);       
   

    }
    /**
     * @Route("/customer",name="customer_landing");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function customerLandingAction(Request $request){
        
       
        return $this->render("@Customer/Default/landing.html.twig");
    }
    
    /**
     * @Route("/customer/profile",name="customer_profile_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function customerProfileAction(Request $request){
        
        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);
        $customer= $this->getUser();
        $form->get('fname')->setData($customer->getFname());
        $form->get('lname')->setData($customer->getLname());
        
        
        return $this->render("@Customer/Account/profile.html.twig",array('form' => $form->createView()));   
        
    }
    
    /**
     * @Route("/customer/forgotpassword",name="customer_forgotpassword_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    
    public function customerforgetpasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
       $randomquestion=1;
       $questionobj =$em->getRepository('Model:SecretQuestion')->findOneBy(['id'=>$randomquestion]);
       $question=$questionobj->getQuestion();
       
            try {
                             
             if (isset($_POST['submit'])) {
                $email= $_POST['email'];
                $customer=$em->getRepository('Model:Customer')->findOneBy(['email'=>$email]);

               if($customer){
               $customerid=$customer->getId();
               $answeredanswer=$_POST['answer'];
               $existinganswerobj=$this->getDoctrine()->getRepository('Model:SecretAnswer')->getAnswerForAQuestion($randomquestion,$customerid);
               $existinganswer=$existinganswerobj[0]->getAnswer();
               $flag=strcmp(strtolower($existinganswer), strtolower($answeredanswer)); 
               if($flag==0){
                   return $this->redirectToRoute("customer_changepassword_page");
               }
               else{
                   return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=> 'Please answer the question again?',
                       'question'=>$question));
               }
               }else{
                   return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=>'This emailid is not registered please try again..',
                       'question'=>$question));
                   
               }
                   
           }
           
       }
       catch (Exception $exception) {
           var_dump($exception);
           die;
       }
       
       
       return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=> '','question'=>$question));
   
      
    }
    /**
     * @Route("/customer/changePassword",name="customer_changepassword_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function customerchangepasswordAction(Request $request){
        
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $customer=$this->getUser();
                $customer->setPassword($this->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form->getData()['password'], ''));
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($customer);
                $entityManager->flush();
                return $this->redirectToRoute("login");
            }
        }
        catch (Exception $exception) {
            var_dump($exception);
            die;
        }
        
        return $this->render("@Customer/Account/changepassword.html.twig", array('form' => $form->createView()));
        
    }
    

}
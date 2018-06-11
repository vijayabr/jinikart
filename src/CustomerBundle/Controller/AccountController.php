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
use CustomerBundle\Form\profileImageuploadType;


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
/* //                 $validityFlag = calling a function ($mobile, $email);

                public static function checkExistance($mobile=null, $email=null) {
                    $flag = true;
//                     DB check query
                    if ($data){
                        $flag = false;
                    }
                    return $flag;
                }
                

//                 $customerMobileNoExist =$em->getRepository('Model:Customer')->findOneBy(['mobileNo'=>$form->getData()["mobile_no"]]);
//     */             $customerEmailExist =$em->getRepository('Model:Customer')->findOneBy(['email'=>$form->getData()["email"]]);
                if(!$customerEmailExist && !$customerMobileNoExist) {
                    $address = new Address();
                    $customer = new Customer();  
                    $customer->setFname($form->getData()["fname"]);
                    $customer->setLname($form->getData()["lname"]);
                    $customer->setEmail($form->getData()["email"]);
                    $customer->setMobileNo($form->getData()["mobile_no"]);
                    $customer->setPassword($form->getData()["password"]);
                    $errors =$validator->validate($customer); 
                    $error1=$validator->validate($address);
                    if(count($errors) > 0 || count($error1)>0){
                        return $this->render("@Customer/Account/register.html.twig", array( 'form' => $form->createView(), 'message'=> '','errors'=>$errors, 'error1'=>$error1 ));
                    }
                    else {              
                        $address->setAddressLine1($addr1);
                        $address->setAddressLine2($addr2);
                        $address->setStateId($state);
                        $address->setCountryId($country);
                        $address->setPincode($pin);
                        $em->persist($address);
                        $em->flush();                        
                        $q1=$em->getRepository('Model:SecretQuestion')->find(1);
                        $q2=$em->getRepository('Model:SecretQuestion')->find(2);
                        $qA1=$em->getRepository('Model:SecretAnswer')->findOneBy(['questionId'=>$q1]);
                        $qA2=$em->getepository('Model:SecretAnswer')->findOneBy(['questionId'=>$q2]);                       
                        $qA1->setAnswer($form->getData()['question1']);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($qA1);
                        $entityManager->flush();                        
                        $qA2->setAnswer($form->getData()['question2']);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($qA2);
                        $entityManager->flush();                       
                        $customer->setAddressId($address);
                        $customer->setCustomerPlanId(Customer_plan::NONPRIME);
                        $customer->setPassword($this->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form->getData()['password'], ''));
                        
                        /**
                         * @var uplodedFile images
                         */
                        $image = $form->getData()["profile_photo"];

                      
                        $imageName = $customer->getFname() . $customer->getLname() . '.' . $image->guessExtension();
                        
                        $image->move($this->getParameter('image_directory'),$imageName);
                        $customer->setProfilePhoto($imageName);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($customer);
                        $entityManager->flush();
                        return $this->redirectToRoute("login");
                    }
                }
                else{
                    $infomessage="you already have an account!!!";
                    return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> $infomessage,'errors'=>'', 'error1'=>'' ));
                }
            }  return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> '','errors'=>'', 'error1'=>'' ));
        }
        catch (\Exception $exception) {
            var_dump($exception);
            die;
        }        
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
        $customer= $this->getUser();
        $image1= $customer->getProfilePhoto();
        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);
        $imageform = $this->createForm(profileImageuploadType::class);
        $imageform->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        try{
            if($imageform->isSubmitted()){
                        
            /**
             * @var uplodedFile images
             */
                $image = $imageform->getData()["profile_photo"];
                $imageName = $customer->getFname() . $customer->getLname() . '.' . $image->guessExtension();
            
                $image->move($this->getParameter('image_directory'),$imageName); 
                $customer->setProfilePhoto($imageName);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($customer); 
                $entityManager->flush();
                return $this->redirectToRoute("customer_profile_page");
            }
            if ($form->isSubmitted()) {
            $customerPlan =$form->getData()["plan"];
            $state=$form->getData()["state"];
            $country=$form->getData()["country"];                    
            $q1=$em->getRepository('Model:SecretQuestion')->find(1);
            $q2=$em->getRepository('Model:SecretQuestion')->find(2);
            $qA1=$em->getRepository('Model:SecretAnswer')->findOneBy(['questionId'=>$q1]);
            $qA2=$em->getRepository('Model:SecretAnswer')->findOneBy(['questionId'=>$q2]);            
            $customerMobileNoExist =$em->getRepository('Model:Customer')->findOneBy(['mobileNo'=>$form->getData()["mobile_no"]]);
            $customerEmailExist =$em->getRepository('Model:Customer')->findOneBy(['email'=>$form->getData()["email"]]);     
            if($customerEmailExist || $customerMobloginileNoExist) {                       
                 if(($customer->getEmail()== $form->getData()["email"]) && ($customer->getMobileNo()== $form->getData()["mobile_no"]) ){
                    $address =$em->getRepository('Model:Address')->findOneBy(['id' => $customer->getAddressId()]);
                    $address->setAddressLine1($form->getData()["address_line1"]);
                    $address->setAddressLine2($form->getData()["address_line2"]);
                    $address->setStateId($state);
                    $address->setCountryId($country);
                    $address->setPincode($form->getData()["pincode"]);
                    $em->persist($address);
                    $em->flush();
                    $customer->setFname($form->getData()["fname"]);
                    $customer->setLname($form->getData()["lname"]);
                    $customer->setEmail($form->getData()["email"]);
                    $customer->setMobileNo($form->getData()["mobile_no"]);
                    $customer->setAddressId($address);
                    $customer->setCustomerPlanId($customerPlan);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($customer);
                    $entityManager->flush();                    
                    $qA1->setAnswer($form->getData()['question1']);
                    $entityManager->persist($qA1);
                    $entityManager->flush();
                    $qA2->setAnswer($form->getData()['question2']);
                    $entityManager->persist($qA2);
                    $entityManager->flush();                    
                    return $this->redirectToRoute("customer_profile_page");
                }
                else{
                $infomessage="you already have an account!!!";
                return $this->render("@Customer/Account/profile.html.twig", 
                    array('form' => $form->createView(),'imageform'=> $imageform->createView(),
                        'message'=> $infomessage, 'image'=> $image1));
                }
            }
            else
            {
            $customer->setFname($form->getData()["fname"]);
            $customer->setLname($form->getData()["lname"]);
            $customer->setEmail($form->getData()["email"]);
            $customer->setMobileNo($form->getData()["mobile_no"]);
            $customer->setAddressId($address);
            $customer->setCustomerPlanId($customerPlan);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();            
            $qA1->setAnswer($form->getData()['question1']);
            $entityManager->persist($qA1);
            $entityManager->flush();            
            $qA2->setAnswer($form->getData()['question2']);
            $entityManager->persist($qA2);
            $entityManager->flush();            
            return $this->redirectToRoute("customer_profile_page");            
           }
       }                 
        $form->get('fname')->setData($customer->getFname());
        $form->get('lname')->setData($customer->getLname());
        $form->get('email')->setData($customer->getemail());
        $form->get('mobile_no')->setData($customer->getmobileNo());
        $address=$em->getRepository('Model:Address')->findOneBy(['id' => $customer->getaddressId()]);        
        $form->get('address_line1')->setData($address->getaddressLine1());
        $form->get('address_line2')->setData($address->getaddressLine2());
        $form->get('pincode')->setData($address->getpincode());
        $state=$em->getRepository('Model:State')->findOneBy(['id' => $address->getstateId()]);
        $form->get('state')->setData($state->getstateName());
        $country=$em->getRepository('Model:Country')->findOneBy(['id' => $address->getcountryId()]);        ;
        $form->get('country')->setData($state->getstateName());
        $customerPlan = $em->getRepository('Model:Customer_plan')->findOneBy(['id' => '1']);        
        $customerPlan = $em->getRepository('Model:Customer_plan')->findOneBy(['id' => $customer->getcustomerPlanId()]);       
        $form->get('plan')->setData($customerPlan->getcustomerPlanName());
        $answers=$this->getDoctrine()->getRepository('Model:SecretAnswer')->getQuestionAnswer($customer->getId());
        $form->get('question1')->setData($answers[0]->getanswer());
        $form->get('question2')->setData($answers[1]->getanswer());        
        }
        catch (\Exception $exception) {
            var_dump($exception);
            die;
        }
        return $this->render("@Customer/Account/profile.html.twig",array('form' => $form->createView(),'imageform'=> $imageform->createView(),'message'=>"",'image'=>$image1));  
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
                $password=$_POST['password1'];
                $confirmPassword=$_POST['password2'];
                $same=strcmp($password, $confirmPassword);
                if($same==0){
                 $customer=$em->getRepository('Model:Customer')->findOneBy(['email'=>$email]);
                  if($customer){
                  $customerid=$customer->getId();
                  $answeredanswer=$_POST['answer'];
                  $existinganswerobj=$this->getDoctrine()->getRepository('Model:SecretAnswer')->getAnswerForAQuestion($randomquestion,$customerid);
                  $existinganswer=$existinganswerobj[0]->getAnswer();
                  $flag=strcmp(strtolower($existinganswer), strtolower($answeredanswer)); 
                  if($flag==0){                 
                    $encodedPassword=$this->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($password, '');
                    $customer->setPassword($encodedPassword);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($customer);
                    $entityManager->flush();
                    return $this->redirectToRoute("login");                                   
                  }
                  else{
                   return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=> 'Please answer the question again?',
                       'question'=>$question));
                   }
                 }
                 else{
                   return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=>'This emailid is not registered please try again..',
                       'question'=>$question));                   
                  }
                }else{
                    return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=>'password and confirm password is not matched, please try again..',
                        'question'=>$question));
                 }
           }           
       }
       catch (Exception $exception) {
           var_dump($exception);die;
       }       
       return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=> '','question'=>$question));         
    }
 }
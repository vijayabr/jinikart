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
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use CommonServiceBundle\Helper\ImageUploader;


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
        $validator=$this->get('validator'); //validator service
        
        try {
           
            if ($form->isSubmitted()) { 

                $em = $this->getDoctrine()->getManager();           
                $customerPlan = $em->getRepository('Model:Customer_plan')->findOneBy(['id' => Customer_plan::DEFAULT_CUSTOMER_PLAN]);
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
                if(!$error1){
                 $em->persist($address);
                 $em->flush();
                }

                $customerMobileNoExist =$em->getRepository('Model:Customer')->findOneBy(['mobileNo'=>$form->getData()["mobile_no"]]);
                $customerEmailExist =$em->getRepository('Model:Customer')->findOneBy(['email'=>$form->getData()["email"]]);

                if(!$customerEmailExist && !$customerMobileNoExist) {
                   
                    $address = new Address();
                    $customer = new Customer();  
                    $customer->setFname($form->getData()["fname"]);
                    $customer->setLname($form->getData()["lname"]);
                    $customer->setEmail($form->getData()["email"]);
                    $customer->setMobileNo($form->getData()["mobile_no"]);
                    $customer->setPassword($form->getData()["password"]);
                    $customer->setCustomerPlanId($customerPlan);
                    $customer->setCustomerStatus(Customer::ACTIVE);
                    $customer->setCustomerRole(Customer::ROLE);
                    $errors =$validator->validate($customer); 
                    $error1=$validator->validate($address);
                    $customer->setAddressId($address);
                    $customer->setPassword($this->get('security.encoder_factory.generic')->getEncoder($customer)->encodePassword($form->getData()['password'], ''));
                    $entityManager = $this->getDoctrine()->getManager();
                   
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
                        $entityManager->persist($customer);
                        $entityManager->flush();
                        $q1=$em->getRepository('Model:SecretQuestion')->find(1);
                        $q2=$em->getRepository('Model:SecretQuestion')->find(2);
                 
                    
                        $qA1= new SecretAnswer();
                        $qA1->setAnswer($form->getData()['question1']);

                        $em = $this->getDoctrine()->getManager();
                        $qA1->setQuestionId($q1); 
                        $qA1->setRole(Customer::ROLE);
                        $qA1->setRoleId($customer);
                        $em->persist($qA1);
                        $em->flush();  
                        
                        $qA2= new SecretAnswer();
                        $qA2->setAnswer($form->getData()['question2']);
                        $qA2->setQuestionId($q2); 
                        $qA2->setRole(Customer::ROLE);
                        $qA2->setRoleId($customer);
                        $em->persist($qA2);
                        $em->flush();     
     

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
                        
                        if($image)
                        {
                           
                        try {  
                            
                            $_FILES['customer']['name']['profile_photo']= $imageName;
                            $file = $_FILES['customer']['tmp_name']['profile_photo'];
                            $keyName = 'profileImage/'. basename($_FILES['customer']['name']['profile_photo']);
                            $pathInS3 = $this->getParameter('aws').$keyName;                            
                            $fileUpload = new ImageUploader($this->container);
                            $fileUpload->imageFileUpload($keyName, $file);
                            
                        } catch (S3Exception $e) {
                            die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
                        } 
                        
               
                       // $image->move($this->getParameter('image_directory'),$imageName);
                        $customer->setProfilePhoto($imageName);
                        }
                       
                        return $this->redirectToRoute("login");
                    }
                }
                else{
                    $infomessage="you already have an account!!!";
                    return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> $infomessage,'errors'=>'', 'error1'=>'' ));
                }
            } 
            return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> '','errors'=>'', 'error1'=>'' ));
        }
        catch (\Exception $exception) {
            var_dump($exception->getMessage().$exception->getLine().$exception->getFile());
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
                if($image)
                {
                    
                    try {
                        
                    $_FILES['profile_imageupload']['name']['profile_photo']= $imageName;
                        $file = $_FILES['profile_imageupload']['tmp_name']['profile_photo'];
                        $keyName = 'profileImage/'. basename($_FILES['profile_imageupload']['name']['profile_photo']);
                        $pathInS3 = $this->getParameter('aws').$keyName;
                        $fileUpload = new ImageUploader($this->container);
                        $fileUpload->DeleteimageFile($keyName);
                        $fileUpload->imageFileUpload($keyName, $file);
                        
                    } catch (S3Exception $e) {
                        die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
                    }
                    
                    
                    // $image->move($this->getParameter('image_directory'),$imageName);
                    $customer->setProfilePhoto($imageName);
                }
               // $image->move($this->getParameter('image_directory'),$imageName); 
                $customer->setProfilePhoto($imageName);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($customer); 
               // $entityManager->flush();
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
            if($customerEmailExist || $customerMobileNoExist) {                       
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
                  $role=$customer->getCustomerRole();
                  $answeredanswer=$_POST['answer'];
                  $existinganswerobj=$this->getDoctrine()->getRepository('Model:SecretAnswer')->getAnswerForAQuestion($randomquestion,$customerid,$role);
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
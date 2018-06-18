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
use \Gumlet\ImageResize;
use CommonServiceBundle\Helper\addressHelper;
use CustomerBundle\Helper\customerDataSetHelper;
use CommonServiceBundle\Helper\questionAnswerHelper;


class AccountController extends Controller
{
    //Function for customer registration
    /**
     * @Route("/customer/registration",name="customer_registration");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function customerRegistrationAction(Request $request)
    {                
        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);
        try {
            $em = $this->getDoctrine()->getManager(); 
            //if form is successfully submitted 
            if ($form->isSubmitted()) {                                         
                $customerPlan = $em->getRepository('Model:Customer_plan')->findOneBy(['id' => Customer_plan::DEFAULT_CUSTOMER_PLAN]);         
                $customerMobileNoExist =$em->getRepository('Model:Customer')->findOneBy(['mobileNo'=>$form->getData()["mobile_no"]]);
                $customerEmailExist =$em->getRepository('Model:Customer')->findOneBy(['email'=>$form->getData()["email"]]);
               //If account doesnot exist create
                if(!$customerEmailExist && !$customerMobileNoExist) {                  
                    $addr=new addressHelper($this->container);
                    $address= $addr->setAddress($form->getData());                   
                    /**
                     * @var uplodedFile images
                     */
                    $image = $form->getData()["profile_photo"];
                    $imageName="";
                    if($image){
                        $imageName = $form->getData()["fname"].$form->getData()["lname"]. '.' . $image->guessExtension();
                        $dest ='profileImage';
                        $fileUpload = new ImageUploader($this->container);
                        $fileUpload->imageFileUpload($image,$imageName,$dest);                                              
                    }                        
                    $customerobj = new customerDataSetHelper($this->container);
                    $customer=$customerobj->setCustomerObject($form->getData(),$address,$customerPlan,$imageName);           
                    if($address instanceof Address && $customer instanceof Customer){    
                        //Secret question and answer for forgot password implementation      
                        $q1=$em->getRepository('Model:SecretQuestion')->find(1);
                        $q2=$em->getRepository('Model:SecretQuestion')->find(2);

                        $qA1= new questionAnswerHelper($this->container);
                        $qA1->setQuestionAnswer($form->getData()["question1"], $q2,$customer->getId());
                        $qA2= new questionAnswerHelper($this->container);
                        $qA2->setQuestionAnswer($form->getData()["question1"], $q2,$customer->getId());
                        return $this->redirectToRoute("login");
                    }else{
                        return $this->render("@Customer/Account/register.html.twig", array( 'form' => $form->createView(), 'message'=> '','errors'=>$customer, 'error1'=>$address ));
                   }                   
                }else{
                    $infomessage="you already have an account!!!";
                    return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> $infomessage,'errors'=>'', 'error1'=>'' ));
                }
            }return $this->render("@Customer/Account/register.html.twig", array('form' => $form->createView(),'message'=> '','errors'=>'', 'error1'=>'' ));
        }
        catch (\Exception $exception) {
           echo "Error Occured in Registration";
        }        
    }    
    
    //Function for sign in or sign up
     /**
     * @Route("/customer",name="customer_landing");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
     public function customerLandingAction(Request $request){
           return $this->render("@Customer/Default/landing.html.twig");
    }
     
    //Function for displaying profile info
    /**
     * @Route("/customer/profile",name="customer_profile_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
        public function customerProfileAction(Request $request){
        $customer= $this->getUser();
        $image= $customer->getProfilePhoto();
        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);
        $imageform = $this->createForm(profileImageuploadType::class);
        $imageform->handleRequest($request);
        $em = $this->getDoctrine()->getManager();      
        try{
            //if form is successfully submitted 
            if($imageform->isSubmitted()){                        
            /**
             * @var uplodedFile images
             */
                if($image)
               {  $imageName = $customer->getFname() . $customer->getLname() . '.' . $image->guessExtension();
                try {
                    $dest ='profileImage';
                    $fileUpload = new ImageUploader($this->container);
                    $fileUpload->imageFileUpload($image,$imageName,$dest);
                }
                catch (S3Exception $e) {
                    die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
                }               
                $customer->setProfilePhoto($imageName);                
                $customer->setProfilePhoto($imageName);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($customer); 
                $entityManager->flush();
                }
                return $this->redirectToRoute("customer_profile_page");
            } //if form is successfully submitted 
            if ($form->isSubmitted()) {
                $customerPlan =$form->getData()["plan"];
                $state=$form->getData()["state"];
                $country=$form->getData()["country"];                    
                $q1=$em->getRepository('Model:SecretQuestion')->find(1);
                $q2=$em->getRepository('Model:SecretQuestion')->find(2);
                $qA1=$em->getRepository('Model:SecretAnswer')->findOneBy(['questionId'=>$q1]);
                $qA2=$em->getRepository('Model:SecretAnswer')->findOneBy(['questionId'=>$q2]);            
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
         //fetch and set the data in the form
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

            echo "Error Occured in Customer Profile Display";
        }
        return $this->render("@Customer/Account/profile.html.twig",array('form' => $form->createView(),'imageform'=> $imageform->createView(),'message'=>"",'image'=>$image1));  

    }    
    //Function for forgot password (changing password)
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
                       'question'=>$question));}
                 }
                 else{
                   return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=>'This emailid is not registered please try again..',
                       'question'=>$question));}
                }else{
                    return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=>'password and confirm password is not matched, please try again..',
                        'question'=>$question));}
           }           
       }
       catch (Exception $exception) {
           echo "Error Occured in Change Password";}       
       return $this->render("@Customer/Account/forgotpassword.html.twig",array('message'=> '','question'=>$question));         
    }
 }
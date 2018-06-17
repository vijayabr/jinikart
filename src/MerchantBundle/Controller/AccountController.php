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
use Exception;
use Common\Model\SecretAnswer;
use Symfony\Component\ExpressionLanguage\Expression;

class AccountController extends Controller
{
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
            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $merchantPlan = $em->getRepository('Model:Merchant_plan')->findOneBy(['id' =>Merchant_plan::DEFAULT_MERCHANT_PLAN]);
               
                $name=$form->getData()["companyName"];                
                $addr1 = $form->getData()["address_line1"];
                $addr2 = $form->getData()["address_line2"];
                $pin = $form->getData()["pincode"];
                $state = $form->getData()["state"];
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
                    $merchant->setmerchantPlanId($merchantPlan);
                    $merchant->setMerchantStatus(Merchant::ACTIVE);
                    $merchant->setMerchantRole(Merchant::ROLE);
                    $errors =$validator->validate($merchant);
                    $error1=$validator->validate($address);
                    if(!$error1){
                        $em->persist($address);
                        $em->flush();
                    }
                 
                    if(count($errors) > 0 || count($error1)>0){
                        return $this->render("@Merchant/Account/register.html.twig", array('form' => $form->createView(), 'message'=> '','errors'=>$errors, 'error1'=>$error1));
                    }
                    else {
                        $address->setAddressLine1($addr1);
                        $address->setAddressLine2($addr2);
                        $address->setStateId($state);
                        $address->setCountryId($country);
                        $address->setPincode($pin);
                        
                        $em->persist($address);
                        $em->flush();     
                        $em->persist($merchant);
                        $em->flush();
                        $q1=$em->getRepository('Model:SecretQuestion')->find(1);
                        $q2=$em->getRepository('Model:SecretQuestion')->find(2);
                    
                        $qA1= new SecretAnswer();
                        $qA1->setAnswer($form->getData()['question1']);
                        $qA1->setQuestionId($q1); 
                        $qA1->setRole(Merchant::ROLE);
                        $qA1->setRoleId($merchant->getId());
                        $em->persist($qA1);
                        $em->flush();
                        
                        $qA2= new SecretAnswer();
                        $qA2->setAnswer($form->getData()['question2']);
                        $qA2->setQuestionId($q2);
                        $qA2->setRole(Merchant::ROLE);
                        $qA2->setRoleId($merchant->getId());
                        $em->persist($qA2);
                        $em->flush();
                        
                        $merchant->setAddressId($address);
                        $merchant->setPassword($this->get('security.encoder_factory.generic')->getEncoder($merchant)->encodePassword($form->getData()['password'], ''));
                    
                       /**
                       *@var uplodedFile images
                       */
                       $image = $form->getData()["companylogo"];
                       if($image){
                        $imageName =  $merchant->getcontactPersonName(). '.' . $image->guessExtension();
                        $image->move($this->getParameter('company_image_directory'),$imageName);
                        $merchant->setCompanyLogo($imageName);
                         } 
                       
               
                        return $this->redirectToRoute("merchant_login"); 
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
            echo "Error occurred while registration";
    }
    
 }
    /**
     * @Route("/merchant/index", name="merchant_index");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function merchantIndexAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->denyAccessUnlessGranted(new Expression(
            '"ROLE_MERCHANT" in roles '));
        try{
        $merchant = $this->getUser();
      
        return $this->render("@Merchant/Default/homepage.html.twig",array('merchant'=>$merchant));
        } catch (\Exception $exception) {
        echo "Error occurred in homepage";
      }
    
    }
    /**
     * @Route("/merchant",name="merchant_landing");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function merchantLandingAction(Request $request){
        try{
        return $this->render("@Merchant/Default/landing.html.twig");
        } catch (\Exception $exception) {
        echo "Error occurred in landing page";
      }
    }
    
    /**
     * @Route("/merchant/forgotpassword",name="merchant_forgotpassword_page");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function merchantforgetpasswordAction(Request $request)
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
                    $merchant=$em->getRepository('Model:Merchant')->findOneBy(['email'=>$email]);
                    
                    if($merchant){
                      
                        $merchantid=$merchant->getId();
                        $role=$merchant->getMerchantRole();
  
                        $answeredanswer=$_POST['answer'];
                        $existinganswerobj=$this->getDoctrine()->getRepository('Model:SecretAnswer')->getAnswerForAQuestion($randomquestion,$merchantid,$role);
                        $existinganswer=$existinganswerobj[0]->getAnswer();
                        $flag=strcmp(strtolower($existinganswer), strtolower($answeredanswer));
                        if($flag==0){
                            $encodedPassword=$this->get('security.encoder_factory.generic')->getEncoder($merchant)->encodePassword($password, '');
                            $merchant->setPassword($encodedPassword);
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($merchant);
                            $entityManager->flush();
                            return $this->redirectToRoute("merchant_login");
                        }
                        else{
                            return $this->render("@Merchant/Account/forgotpassword.html.twig",array('message'=> 'Please answer the question again?',
                                'question'=>$question));
                        }
                    }
                    else{
                        return $this->render("@Merchant/Account/forgotpassword.html.twig",array('message'=>'This emailid is not registered please try again..',
                            'question'=>$question));
                    }
                }else{
                    return $this->render("@Merchant/Account/forgotpassword.html.twig",array('message'=>'password and confirm password is not matched, please try again..',
                        'question'=>$question));
                }
            }
        }
        catch (Exception $exception) {
            echo "Error while changing Password";
        }
        return $this->render("@Merchant/Account/forgotpassword.html.twig",array('message'=> '','question'=>$question));
    }
    
}
        


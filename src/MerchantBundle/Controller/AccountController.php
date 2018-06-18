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
use CommonServiceBundle\Helper\ImageUploader;
use CommonServiceBundle\Helper\addressHelper;
use CommonServiceBundle\Helper\questionAnswerHelper;
use MerchantBundleBundle\Helper\merchantDataSetHelper;

class AccountController extends Controller
{
    //Function for merchant registration
     /**
     * @Route("/merchant/registration", name="merchant_registration");
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function merchantRegistrationAction(Request $request)
    {
        
        
        $form = $this->createForm(MerchantType::class);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $merchantPlan = $em->getRepository('Model:Merchant_plan')->findOneBy(['id' =>Merchant_plan::DEFAULT_MERCHANT_PLAN]);                                  
                $merchantMobileNoExist =$em->getRepository('Model:Merchant')->findOneBy(['mobileNo'=>$form->getData()["mobileNo"]]);
                $merchantEmailExist =$em->getRepository('Model:Merchant')->findOneBy(['email'=>$form->getData()["email"]]);
                if(!$merchantEmailExist && !$merchantMobileNoExist) { 
                    $addr=new addressHelper($this->container);
                    $address= $addr->setAddress($form->getData());      
                   //Uploading image to s3
                    $image = $form->getData()["companylogo"];
                    if($image){
                        $imageName = $form->getData()["companyName"]. '.' . $image->guessExtension();
                        $dest ='companyLogo';
                        $fileUpload = new ImageUploader($this->container);
                        $fileUpload->imageFileUpload($image,$imageName,$dest);    
                    }  
                    $customerobj = new merchantDataSetHelper($this->container);
                    $customer=$customerobj->setMerchantObject($form->getData(),$address,$merchantPlan,$imageName);                                
                                   
                    if($address instanceof Address && $merchant instanceof Merchant){
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
                }else
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
 //Function for displaying homepage
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
    //Function for sign in or sign up
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
    //Function for forgot password (changing password)
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
        


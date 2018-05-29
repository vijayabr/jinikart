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

                $customerPlan = $em->getRepository('Model:Customer_plan')->findOneBy(['id' => 1]);
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
                    $customer->setPassword($this->get('security.encoder_factory')->getEncoder($customer)->encodePassword($form->getData()['password'], ''));

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

}
<?php
/**
 * Created by PhpStorm.
 * User: techjini
 * Date: 22/5/18
 * Time: 10:15 AM
 */

namespace CustomerBundle\Form;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\Mapping\Entity;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fname', TextType::class, array('label' => 'First Name:'))
            ->add('lname', TextType::class, array('label' => 'Last Name:'))
            ->add('email', TextType::class, array('label' => 'Email Id:'))
            ->add('mobile_no', TextType::class, array('label' => 'Mobile Number:'))
            ->add('address_line1', TextType::class, array('label' => 'Address_line1:'))
            ->add('address_line2', TextType::class, array('label' => 'Address_line2:'))
            ->add('state', EntityType::class, array('class' => 'Common\Model\State',
                'choice_label' => function ($state) {
                    return $state->getStateName();
                }))
            ->add('country', EntityType::class, array('class' => 'Common\Model\Country',
                'choice_label' => function ($country) {
                    return $country->getCountryName();
                }))
            ->add('pincode',TextType::class, array('label' => 'Pincode:'))
            ->add('question1',TextType::class,array('label'=>'what is your favourite color?'))
            ->add('question2',TextType::class,array('label'=>'which is your favourite food?'))
            ->add('plan', EntityType::class, array('class' => 'Common\Model\Customer_plan',
                'choice_label' => function ($state) {
                return $state->getCustomerPlanName();
                }))
            ;
             }
}
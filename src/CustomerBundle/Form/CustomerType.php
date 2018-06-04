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

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fname', TextType::class, array('label' => 'First Name:'))
            ->add('lname', TextType::class, array('label' => 'Last Name:'))
            ->add('email', TextType::class, array('label' => 'Email Id:'))
            ->add('mobile_no', TextType::class, array('label' => 'Mobile Number:'))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password:'),
                'second_options' => array('label' => 'Confirm Password:'),
            ))
            ->add('address_line1', TextType::class, array('label' => 'Address_line1:'))
            ->add('address_line2', TextType::class, array('label' => 'Address_line2:'))
            ->add('state', EntityType::class, array('class' => 'Common\Model\State',
                'choice_label' => function ($state) {
                    return $state->getStateName();
                },'placeholder' => 'Choose an option'))
            ->add('country', EntityType::class, array('class' => 'Common\Model\Country',
                'choice_label' => function ($country) {
                    return $country->getCountryName();
                },'placeholder' => 'Choose an option'))
            ->add('pincode',TextType::class, array('label' => 'Pincode:'))
            ->add('profile_photo', FileType::class, array('label' => 'Profile photo'))
            ->add('question1',TextType::class,array('label'=>'what is your favourite color?'))
            ->add('question2',TextType::class,array('label'=>'which is your favourite food?'))
            
        ;

    }


}
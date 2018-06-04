<?php
namespace MerchantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;




class MerchantType extends AbstractType
{
   
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('companyName', TextType::class, array('label' => 'Comapany Name:'))
            ->add('contactPersonName', TextType::class, array('label' => 'Contact Person Name:'))
            ->add('address_line1', TextType::class, array('label' => 'Address_line1:'))
            ->add('address_line2', TextType::class, array('label' => 'Address_line2:'))
            ->add('email', TextType::class, array('label' => 'Email Id:'))
            ->add('mobile_no', TextType::class, array('label' => 'Mobile Number:'))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password:'),
                'second_options' => array('label' => 'Confirm Password:'),
            ))
            ->add('state', EntityType::class, array('class' => 'Common\Model\State',
                'choice_label' => function ($state) {
                return $state->getStateName();
                }))
            ->add('country', EntityType::class, array('class' => 'Common\Model\Country',
                    'choice_label' => function ($country) {
                    return $country->getCountryName();
                    }))
            ->add('pincode',TextType::class, array('label' => 'Pincode:'))
            ->add('companylogo', FileType::class, array('label' => 'Company Logo'));
            
         }
}


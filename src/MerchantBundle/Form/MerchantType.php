<?php
namespace src\MerchantBundle\Form;

class MerchantType extends AbstractType
{
   
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('companyName', TextType::class, array('label' => 'Comapany Name:'))
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
            ->add('profile_photo', FileType::class, array('label' => 'Profile photo'));
            
         }
}


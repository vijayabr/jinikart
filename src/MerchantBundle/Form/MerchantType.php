<?php
namespace MerchantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Length;


class MerchantType extends AbstractType
{
   
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('companyName', TextType::class, array('label' => 'Comapany Name:'))
            ->add('contactPersonName', TextType::class, array('label' => 'Contact Person Name:', 'required'=>''))
            ->add('address_line1', TextType::class, array('label' => 'Address_line1:','required'=>false))
            ->add('address_line2', TextType::class, array('label' => 'Address_line2:','required'=>false))
            ->add('email', EmailType::class, array('label' => 'Email Id:','required'=>''))
            ->add('mobileNo', TelType::class, array('label' => 'Mobile Number:',
                'required'=>''))
            
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password:'),
                'second_options' => array('label' => 'Confirm Password:','required'=>''),
            ))
            ->add('state', EntityType::class, array('class' => 'Common\Model\State',
                'choice_label' => function ($state) {
                return $state->getStateName();
                },'placeholder' => 'Choose a state'))
            ->add('country', EntityType::class, array('class' => 'Common\Model\Country',
                    'choice_label' => function ($country) {
                    return $country->getCountryName();
                    },'placeholder' => 'Choose a country'))
                    ->add('pincode',TextType::class, array('label' => 'Pincode:','required'=>false))
            ->add('companylogo', FileType::class, array('label' => 'Company Logo', 'required'=>false));
            
         }
}
?>


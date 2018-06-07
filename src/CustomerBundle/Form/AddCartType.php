<?php namespace CustomerBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class AddCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('product_count',ChoiceType::class,array(
        'choices'=>array('1','2')   
        ,'label'=> 'Quantity' , 'placeholder' => 'Choose an option'))
        ->add('submit',SubmitType::class);     
    }
    
}
?>
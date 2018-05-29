<?php
namespace MerchantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product_name',TextType::class,array('label' => 'Product Name'))
        ->add('product_price',TextType::class,array('label' => 'Product Price'))
        ->add('productIMEI',TextType::class,array('attr'=> array('class' => 'Model:Product_Detail_List',
            'label'=> 'IMEI_No'),
         ))
        ->add('product_discount',PercentType::class,array('label' => 'Discount'))
        ->add('category',TextType::class,array('attr'=> array('class' => 'Model:Category',
            'label'=> 'Category'),
        ))
        ->add('brand',TextType::class,array('attr'=> array('class' => 'Model:Brand',
            'label'=> 'Brand'),
        ))
        ->add('color',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Color'),
        ))
        ->add('ram_size',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Ram size'),
        ))
        ->add('camera',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Camera'),
        ))
        
        ->add('product_complete_info',TextareaType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Description'),
        ))
        ->add('product_photo', FileType::class, array('label' => 'Product Photo'))
        ->add('add',SubmitType::class, array('label' => 'Add'))
        ->add('clear',ResetType::class, array('label' => 'Clear'));
    }
}
?>
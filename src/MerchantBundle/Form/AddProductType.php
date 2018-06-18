<?php
namespace MerchantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Length;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product_name',TextType::class,array('attr'=> array('class' => 'Model:Product_Detail_List','placeholder'=>"product name",
                'label' => 'Product Name: ')))
                ->add('product_price',TextType::class,array('attr'=> array('class' => 'Model:Product_Detail_List','placeholder'=>"price in rupees",
                    'label' => 'Product Price: ')))
        ->add('productIMEI',TextType::class,array('attr'=> array('class' => 'Model:Product_Detail_List','placeholder'=>"15 digit unique number",
            
            'label'=> 'IMEI:'),'constraints'=>array(new Length(array('min'=>15))),'required'=>''))
            ->add('product_discount',PercentType::class,array('attr'=> array('class' => 'Model:Product_Detail_List','placeholder'=>"percentage in digits",
                'label' => 'Discount:')))
        ->add('categoryName',EntityType::class,array('class' => 'Common\Model\Category',
            'choice_label'=> function($cat){
            return $cat->getCategoryName();
            }, 'placeholder' => 'Choose an option','label' =>'Category Name:'))
        ->add('brandName',EntityType::class,array('class' => 'Common\Model\Brand',
            'choice_label'=> function($brand){
              return $brand->getBrandName();
            },'placeholder' => 'Choose an option','label' =>'Brand Name:'))
        ->add('color',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Color:'),
        ))
        ->add('ram_size',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Ram size:'),
        ))
        ->add('camera',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Camera:'),
        ))
        ->add('product_complete_info',TextareaType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Description:'),
        ))
        ->add('product_photo', FileType::class, array('label' => 'Product Photo:'));
       
    }
}
?>

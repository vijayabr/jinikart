<?php


namespace MerchantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;


class UpdateProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product_name',TextType::class,array('label' => 'Product Name: ','required'=>false))
        ->add('product_price',TextType::class,array('label' => 'Product Price: ','required'=>false))
        
        ->add('product_discount',PercentType::class,array('label' => 'Discount:','required'=>false))
        ->add('categoryName',EntityType::class,array('class' => 'Common\Model\Category',
            'choice_label'=> function($cat){
            return $cat->getCategoryName();
            }, 'placeholder' => 'Choose an option','label' =>'Category Name:'))
            ->add('brandName',EntityType::class,array('class' => 'Common\Model\Brand',
                'choice_label'=> function($brand){
                return $brand->getBrandName();
                },'placeholder' => 'Choose an option','label' =>'Brand Name:'))
        ->add('color',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Color:'),'required'=>false
                ))
        ->add('ram_size',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Ram size:'),'required'=>false
                ))
        ->add('camera',TextType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Camera:'),'required'=>false
                ))
                
        ->add('product_complete_info',TextareaType::class,array('attr'=> array('class' => 'Model:Product_Description',
            'label'=> 'Description:'),'required'=>false
                ))
                ->add('product_photo', FileType::class, array('label' => 'Product Photo:','required'=>false));
                
    }
}
?>

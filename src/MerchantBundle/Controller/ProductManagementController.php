<?php

namespace MerchantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Common\Model\Product;


class ProductManagementController extends Controller
{
    /**
     * @Route("/add",name="add_products");
     * @param Request $Request
     */
    
    public function addProductAction(Request $Request){
        
        $product= new Product();
        $form = $this->createFormBuilder($product)
        ->add('product_name',TextType::class,array('label' => 'Product Name'))
        ->add('product_price',IntegerType::class,array('label' => 'Product Name'))
        ->add('productIMEI',IntegerType::class,array('label' => 'IMEI_NO','class'=>'Common\Model\Product_Detail_List'))
        ->add('product_discount',PercentType::class,array('label' => 'Discount'))
        ->add('category',TextType::class,array('label' => 'Category','class'=>'Common\Model\Category'))
        ->add('brand',TextType::class,array('label' => 'Brand','class'=>'Common\Model\Brand'))
        ->add('color',TextType::class,array('label' => 'Color','class'=>'Common\Model\Product_Description'))
        ->add('ram_size',TextType::class,array('label' => 'Ram Size','class'=>'Common\Model\Product_Description'))
        ->add('camera', TextType::class,array('label' => 'Camera','class'=>'Common\Model\Product_Description'))
        ->add('product_complete_info',TextareaType::class,array('label' => 'Description','class'=>'Common\Model\Product_Description'))
        ->add('add',SubmitType::class, array('label' => 'Add'))
        ->add('clear',ResetType::class, array('label' => 'Clear'))
        ->getForm();
       
        return $this->render("@Merchant/Default/add.html.twig",array('form' => $form->createView()));
    }
    
    /**
     * @Route("/list",name="list_products");
     * @param Request $Request
     */
    
    public function listProductAction(Request $Request){
        
        return $this->render("@Merchant/Default/list.html.twig");
    }
    /**
     * @Route("/details",name="company_details");
     * @param Request $request
     */
    public function detailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $merchant=$em->getRepository('Model:Merchant')->findOneBy(['id'=>1]);
       
        return $this->render("@Merchant/Account/detail.html.twig",array('merchant'=> $merchant));
        
    }
  
}

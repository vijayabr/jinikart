<?php
/**
 * Created by PhpStorm.
 * User: techjini
 * Date: 22/5/18
 * Time: 9:58 AM
 */

namespace CustomerBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class countryType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('country_name', 'text',array('required'=>true));
    }
}
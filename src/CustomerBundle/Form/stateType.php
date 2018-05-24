<?php
/**
 * Created by PhpStorm.
 * User: techjini
 * Date: 22/5/18
 * Time: 9:55 AM
 */

namespace CustomerBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class stateType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder->add('state_name', 'text',array('required'=>true));
    }

}
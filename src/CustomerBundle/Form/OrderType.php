<?php 
namespace CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ivory\GoogleMap\Place\AutocompleteComponentType;
use Ivory\GoogleMapBundle\Form\Type\PlaceAutocompleteType;

class OrderType extends AbstractType
{
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
        
//         $builder->add('address_line1', PlaceAutocompleteType::class,array('attr'=> array('class' => 'Model:Address',
//             ['components' => [AutocompleteComponentType::LOCALITY]
            
//         ])))
//         ->add('address_line2', PlaceAutocompleteType::class,array('attr'=> array('class' => 'Model:Address',
//           )))
//         ->add('pincode', PlaceAutocompleteType::class,array('attr'=> array('class' => 'Model:Address',
//                )));
          
//     }
}
?>
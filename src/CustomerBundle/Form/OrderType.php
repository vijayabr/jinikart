<?php 
namespace CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ivory\GoogleMap\Place\AutocompleteComponentType;
use Ivory\GoogleMap\Base\Bound;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMapBundle\Form\Type\PlaceAutocompleteType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('field', PlaceAutocompleteType::class,[
            'variable' => 'place_autocomplete','components' => [AutocompleteComponentType::COUNTRY => 'in'
        ]]);
        
    }
}
?>
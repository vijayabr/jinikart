<?php 
namespace CommonServiceBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ProductDeliveryCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this->setName('product:delivey')
           ->setDescription('change the product order status to delivered');
            
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {  $time = new \DateTime();
       $em = $this->getContainer()->get('doctrine')->getEntityManager();
       $datetime = new \DateTime();
       $datetime= $datetime->sub(new \DateInterval('P1D'));
       $productOrderDetail=$em->getRepository('Model:ProductOrderDetail')->productDelivered($datetime);
       foreach ($productOrderDetail as $productOrder){
           $productOrder=$em->getRepository('Model:ProductOrderDetail')->find($productOrder);
           $productOrder->setOrderStatus("Delivered");
           $productOrder->setDeliveryDate($time);
           $em->persist($productOrder);
           $em->flush();
       }
       $productOrderDetail=$em->getRepository('Model:ProductOrderDetail')->productDelivered($time);
       return(1);
    }
}   
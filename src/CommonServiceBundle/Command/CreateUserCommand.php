<?php 
namespace CommonServiceBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use CommonServiceBundle\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
class CreateUserCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this->setName('merchant:stockstatus')
           ->setDescription('status of the each product merchant added products.');
            
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $mailer = $this->getContainer()->get('mailer');
       $em = $this->getContainer()->get('doctrine')->getEntityManager();
       $directory=$this->getContainer()->getParameter('product_file_directory');
       $merchants = $em->getRepository('Model:Merchant')->findAll();
       foreach ($merchants as $merchant){
           $filePath=$directory."//".$merchant->getcompanyName().".pdf";
           $body="Hello sir/Madam, \nPlease find the attached file \n Thank you";
           $subject='stock status';
           $to=$merchant->getEmail();
           $email =new DefaultController();
           $email->mailSending($mailer,$to, $body,$subject,$filePath);
        
       }
       $output->writeln($email);   
       return(1);
    }
}   
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
       $a =new DefaultController();
       
       $a->mailSending($mailer ,'aishwaryamk96@gmail.com', 'hii','test');                
        $output->writeln($a);
       
    }
}
<?php

namespace CommonServiceBundle\Helper;
use Symfony\Component\DependencyInjection\Container;
use Aws\S3\S3Client;
use Symfony\Component\Config\Definition\Exception\Exception;
use Aws\S3\Exception\S3Exception;
use Gumlet\ImageResize;
use Symfony\Component\HttpFoundation\File\File;

class fileUploader{
    public $bucketName;
    public $IAM_KEY;
    public $IAM_SECRET;
    public  $container;
    public $s3;
    
    public function __construct(Container $container){
        $this->container=$container;
        $this->bucketName =  $this->container->getParameter('bucket');
        $this->IAM_KEY = $this->container->getParameter('IAM_KEY');
        $this->IAM_SECRET = $this->container->getParameter('IAM_SECRET');
        try{
            $this->s3=S3Client::factory(
                array(
                    'credentials' => array(
                        'key' => $this->IAM_KEY,
                        'secret' => $this->IAM_SECRET
                    ),
                    'version' => 'latest',
                    'region'  => 'ap-south-1'
                ));            
        }catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }               
    }
    
    public function fileUploading($keyName,$file){
        try{  $result=$this->s3->putObject(
            array(
                'Bucket'=>$this->bucketName,
                'Key' => $keyName,
                'SourceFile' => $file,
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'ACL'  => 'public-read'
            )
            );
        }
        catch (S3Exception $e){
            die('Error:'.$e->getMessage());
        }
    }
    
   public function pdfFileUpload($file, $file){
            try {
           
            
        }
        catch (Exception $e) {
            die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
        }
        
        
    }
    
   
}



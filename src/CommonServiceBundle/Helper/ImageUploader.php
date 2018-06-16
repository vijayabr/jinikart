<?php

namespace CommonServiceBundle\Helper;




use Symfony\Component\DependencyInjection\Container;
use Aws\S3\S3Client;
use Symfony\Component\Config\Definition\Exception\Exception;
use Aws\S3\Exception\S3Exception;

class ImageUploader{
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
                )
                );
            
        }catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
        
        
    }
   
    public function imageFileUpload($keyName,$file){
    
        try {
            // Uploaded:
           
                $result=$this->s3->putObject(
                array(
                    'Bucket'=>$this->bucketName,
                    'Key' => $keyName,
                    'SourceFile' => $file,
                    'StorageClass' => 'REDUCED_REDUNDANCY',
                    'ACL'  => 'public-read'
                )
                );
            return $result;
        } 
        catch (S3Exception $e) {
            die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
        } 
        
    }
    
    public function getImage($keyName)
    {       
    try {
        $result = $this->s3->getObject([
            'Bucket' => $this->bucketName,
            'Key'    => $keyName
        ]);
        
        return $result;
    } catch (S3Exception $e) {
        die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
    }
    }
    
    public function DeleteimageFile($keyName)
    {
        try {
            
           $result= $this->s3->deleteObject(
               array(
                'Bucket' => $this->bucketName,
                'Key'    => $keyName
            ));
           
            return $result;
        } catch (S3Exception $e) {
            die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
        }
    }
}



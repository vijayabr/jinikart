<?php

namespace CommonServiceBundle\Helper;
use Symfony\Component\DependencyInjection\Container;
use Aws\S3\S3Client;
use Symfony\Component\Config\Definition\Exception\Exception;
use Aws\S3\Exception\S3Exception;
use Gumlet\ImageResize;
use Symfony\Component\HttpFoundation\File\File;

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
                ));            
        }catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }               
    }
    
    public function imageUpload($keyName,$file){
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
   public function tumbmilImage($image,$imageName,$dest)
   {       try {          
           $imageSize= new ImageResize($image);
           $image = $imageSize->resize(75,100);     
           $temp = $this->container->getParameter('image_directory')."/temp.png";
           $image = $image->save($temp);
           $keyName = 'tumbmilImage/'.$dest.'/'. basename($imageName);
           $this->imageUpload($keyName,$temp);           
       }
       catch (Exception $e) {
           die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
       }       
   }
   
   public function smallImage($image,$imageName,$dest)
   {
       try {
           $imageSize= new ImageResize($image);
           $image = $imageSize->resize(180,240);
           $temp = $this->container->getParameter('image_directory')."/temp.png";
           $image = $image->save($temp);
           $keyName = 'smallsizeimage/'.$dest.'/'. basename($imageName);
           $this->imageUpload($keyName,$temp);           
       }
       catch (Exception $e) {
           die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
       }       
   }
    
   public function mediumImage($image,$imageName,$dest)
   {
   try {
       $imageSize= new ImageResize($image);
       $image = $imageSize->resize(375,500 );
       $temp = $this->container->getParameter('image_directory')."/temp.png";
       $image = $image->save($temp);
       $keyName = 'mediumsize/'.$dest.'/'. basename($imageName);
       $this->imageUpload($keyName,$temp);       
   }
   catch (Exception $e) {
       die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
   }
   }
   
   public function largeImage($image,$imageName,$dest)
   { 
   try {
       $imageSize= new ImageResize($image);
       $image = $imageSize->resize(768,1024);
       $temp = $this->container->getParameter('image_directory')."/temp.png";
       $image = $image->save($temp);
       $keyName = 'largesize/'.$dest.'/'. basename($imageName);
       $this->imageUpload($keyName,$temp);       
   }
   catch (Exception $e) {
       die('Error:' . $e->getMessage().$e->getLine().$e->getFile());
   }       
   }
    
   public function imageFileUpload($image, $imageName,$dest){
            try {
            $this->tumbmilImage($image,$imageName,$dest);
            $this->smallImage($image, $imageName, $dest);
            $this->mediumImage($image, $imageName, $dest);
            $this->largeImage($image, $imageName, $dest);
            
        }
        catch (Exception $e) {
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



<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product_Description
 *
 * @ORM\Table(name="product__description")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\Product_DescriptionRepository")
 */
class Product_Description
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=50)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="ram_size", type="string", length=50)
     */
    private $ramSize;
    
    /**
     * @var string
     *
     * @ORM\Column(name="camera", type="string", length=20)
     */
    private $camera;
    
    /**
     * @var string
     *
     * @ORM\Column(name="camera", type="string", length=50)
     */
    private $camera;
    /**
     * @var string
     *
     * @ORM\Column(name="product_complete_info", type="text")
     */
    private $productCompleteInfo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    
    public function __construct() {
        
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
    }
   
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Product_Description
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set ramSize
     *
     * @param string $ramSize
     *
     * @return Product_Description
     */
    public function setRamSize($ramSize)
    {
        $this->ramSize = $ramSize;

        return $this;
    }

    /**
     * Get ramSize
     *
     * @return string
     */
    public function getRamSize()
    {
        return $this->ramSize;
    }
    /**
     * Set color
     *
     * @param string $color
     *
     * @return Product_Description
     */
    public function setCamera($camera)
    {
        $this->camera = $camera;
        
        return $this;
    }
    
    /**
     * Get color
     *
     * @return string
     */
    public function getCamera()
    {
        return $this->camera;
    }
    
    
    /**
     * Set camera
     *
     * @param string $camera
     *
     * @return Product_Description
     */
    public function setCamera($camera)
    {
        $this->camera = $camera;
        
        return $this;
    }
    
    /**
     * Get camera
     *
     * @return string
     */
    public function getCamera()
    {
        return $this->camera;
    }
    
    
    /**
     * Set productCompleteInfo
     *
     * @param string $productCompleteInfo
     *
     * @return Product_Description
     */
    public function setProductCompleteInfo($productCompleteInfo)
    {
        $this->productCompleteInfo = $productCompleteInfo;

        return $this;
    }

    /**
     * Get productCompleteInfo
     *
     * @return string
     */
    public function getProductCompleteInfo()
    {
        return $this->productCompleteInfo;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product_Description
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Product_Description
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}


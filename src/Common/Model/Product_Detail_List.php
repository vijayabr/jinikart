<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\BigIntType;

/**
 * Product_Detail_List
 *
 * @ORM\Table(name="product__detail__list")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\Product_Detail_ListRepository")
 */
class Product_Detail_List
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Common\Model\Product")
     * @ORM\JoinColumn(name="productId", referencedColumnName="id")
     */
    private $productId;

    /**
     * @var string
     * @ORM\Column(name="productIMEI", type="string", unique=true)
     */
    private $productIMEI;
    
    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Common\Model\Merchant")
     * @ORM\JoinColumn(name="merchantId", referencedColumnName="id")
     */
    private $merchantId;

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
     * Set merchantId
     *
     * @param integer $merchantId
     *
     * @return Product_Detail_List
     */
    public function setMerchantId($merchantId)
    {
        $this-> merchantId = $merchantId;
        
        return $this;
    }
    
    /**
     * Get merchantId
     *
     * @return int
     */
    public function getMerchantId()
    {
        return $this-> merchantId;
    }
    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return Product_Detail_List
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set productIMEI
     *
     * @param string $productIMEI
     *
     * @return Product_Detail_List
     */
    public function setProductIMEI($productIMEI)
    {
        $this->productIMEI = $productIMEI;

        return $this;
    }

    /**
     * Get productIMEI
     *
     * @return string
     */
    public function getProductIMEI()
    {
        return $this->productIMEI;
    }

    /**
     * Set merchantId
     *
     * @param integer $merchantId
     *
     * @return Product_Detail_List
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        
        return $this;
    }
    
    /**
     * Get merchantId
     *
     * @return int
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product_Detail_List
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
     * @return Product_Detail_List
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}


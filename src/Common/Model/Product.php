<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\ProductRepository")
 */
class Product 
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="product_name", type="string", length=20)
     */
    private $productName;

    /**
     * @var int
     * One Product can has one description Id.
     * @ORM\OneToOne(targetEntity="Common\Model\Product_Description")
     * @ORM\JoinColumn(name="productDescriptionId", referencedColumnName="id")
     */
    private $productDescriptionId;

    /**
     * @var float
     *
     * @ORM\Column(name="product_price", type="float")
     */
    private $productPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon", type="string", nullable=true)
     */
    private $coupon;
    /**
     * @var float
     *
     * @ORM\Column(name="product_discount", type="float", nullable=true)
     */
    private $productDiscount;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Common\Model\Merchant")
     * @ORM\JoinColumn(name="merchantId", referencedColumnName="id")
     */
    private $merchantId;
    /**
     * @var int
     * One Product can have one category.
     * @ORM\ManyToOne(targetEntity="Common\Model\Category")
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="id")
     */
    private $categoryId;

    /**
     * @var int
     * One Product can be of one brand.
     * @ORM\ManyToOne(targetEntity="Common\Model\Brand")
     * @ORM\JoinColumn(name="brandId", referencedColumnName="id")
     */
    private $brandId;

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
     * Set productName
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set productDescriptionId
     *
     * @param integer $productDescriptionId
     *
     * @return Product
     */
    public function setProductDescriptionId($productDescriptionId)
    {
        $this->productDescriptionId = $productDescriptionId;

        return $this;
    }

    /**
     * Get productDescriptionId
     *
     * @return int
     */
    public function getProductDescriptionId()
    {
        return $this->productDescriptionId;
    }

    /**
     * Set productPrice
     *
     * @param float $productPrice
     *
     * @return Product
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    /**
     * Get productPrice
     *
     * @return float
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * Set productDiscount
     *
     * @param float $productDiscount
     *
     * @return Product
     */
    public function setProductDiscount($productDiscount)
    {
        $this->productDiscount = $productDiscount;

        return $this;
    }

    /**
     * Get productDiscount
     *
     * @return float
     */
    public function getProductDiscount()
    {
        return $this->productDiscount;
    }

    /**
     * Set coupon
     *
     * @param string $coupon
     *
     * @return Product
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
        
        return $this;
    }
    
    /**
     * Get coupon
     *
     * @return string
     */
    public function getCoupon()
    {
        return $this->coupon;
    }
    /**
     * Set merchantId
     *
     * @param integer $merchantId
     *
     * @return Product
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
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return Product
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set brandId
     *
     * @param integer $brandId
     *
     * @return Product
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;

        return $this;
    }

    /**
     * Get brandId
     *
     * @return int
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product
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
     * @return Product
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
    public function getPassword()
    {}

    public function eraseCredentials()
    {}

    public function getSalt()
    {}

    public function getRoles()
    {}

    public function getUsername()
    {}

}


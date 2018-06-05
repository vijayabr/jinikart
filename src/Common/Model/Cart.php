<?php

namespace MerchantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="MerchantBundle\Repository\CartRepository")
 */
class Cart
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
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer", unique=true)
     * 
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="customer_id", type="integer", unique=true)
     */
    private $customerId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_count", type="integer")
     */
    private $productCount;

    /**
     * @var int
     *
     * @ORM\Column(name="cart_list_id", type="integer", unique=true)
     */
    private $cartListId;

    /**
     * @var bool
     *
     * @ORM\Column(name="cart_status", type="boolean")
     */
    private $cartStatus;

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
     * Set productId
     *
     * @param integer $productId
     *
     * @return Cart
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
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Cart
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set productCount
     *
     * @param integer $productCount
     *
     * @return Cart
     */
    public function setProductCount($productCount)
    {
        $this->productCount = $productCount;

        return $this;
    }

    /**
     * Get productCount
     *
     * @return int
     */
    public function getProductCount()
    {
        return $this->productCount;
    }

    /**
     * Set cartListId
     *
     * @param integer $cartListId
     *
     * @return Cart
     */
    public function setCartListId($cartListId)
    {
        $this->cartListId = $cartListId;

        return $this;
    }

    /**
     * Get cartListId
     *
     * @return int
     */
    public function getCartListId()
    {
        return $this->cartListId;
    }

    /**
     * Set cartStatus
     *
     * @param boolean $cartStatus
     *
     * @return Cart
     */
    public function setCartStatus($cartStatus)
    {
        $this->cartStatus = $cartStatus;

        return $this;
    }

    /**
     * Get cartStatus
     *
     * @return bool
     */
    public function getCartStatus()
    {
        return $this->cartStatus;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cart
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
     * @return Cart
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


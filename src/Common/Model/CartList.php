<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartList
 *
 * @ORM\Table(name="cart_list")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\CartListRepository")
 */
class CartList
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
     *  @ORM\ManyToOne(targetEntity="Common\Model\Cart")
     * @ORM\JoinColumn(name="cartId", referencedColumnName="id")
     * 
     */
    private $cartId;
    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Common\Model\Product_Detail_List")
     * @ORM\JoinColumn(name="productIMEI", referencedColumnName="id")
     */
    private $productIMEI;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get cartListId
     *
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }
    
    /**
     * Set cartId
     *
     * @param integer $cartId
     *
     * @return Cart
     */
    public function setCartId($cartListId)
    {
        $this->cartId = $cartId;
        
        return $this;
    }
    
    
    /**
     * Set productIMEI
     *
     * @param integer $productIMEI
     *
     * @return CartList
     */
    public function setProductId($productIMEI)
    {
        $this->productId = $productIMEI;

        return $this;
    }

    /**
     * Get productIMEI
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->$productIMEI;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CartList
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
     * @return CartList
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


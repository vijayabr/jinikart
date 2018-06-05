<?php

namespace MerchantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOrderDetail
 *
 * @ORM\Table(name="product_order_detail")
 * @ORM\Entity(repositoryClass="MerchantBundle\Repository\ProductOrderDetailRepository")
 */
class ProductOrderDetail
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
     * @ORM\Column(name="product_order_id", type="integer", unique=true)
     */
    private $productOrderId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="datetime")
     */
    private $deliveryDate;

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
     * Set productOrderId
     *
     * @param integer $productOrderId
     *
     * @return ProductOrderDetail
     */
    public function setProductOrderId($productOrderId)
    {
        $this->productOrderId = $productOrderId;

        return $this;
    }

    /**
     * Get productOrderId
     *
     * @return int
     */
    public function getProductOrderId()
    {
        return $this->productOrderId;
    }

    /**
     * Set deliveryDate
     *
     * @param \DateTime $deliveryDate
     *
     * @return ProductOrderDetail
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ProductOrderDetail
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
     * @return ProductOrderDetail
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


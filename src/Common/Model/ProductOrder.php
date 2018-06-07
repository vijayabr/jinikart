<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOrder
 *
 * @ORM\Table(name="product_order")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\ProductOrderRepository")
 */
class ProductOrder
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
     * @ORM\OneToOne(targetEntity="Common\Model\Customer")
     * @ORM\JoinColumn(name="customerId", referencedColumnName="id")
     */
    private $customerId;

    /**
     * @var \DateTime
     * @ORM\Column(name="ordered_date", type="datetime")
     */
    private $orderedDate;

    /**
     * @var int
     *one customer has one default address
     * @ORM\ManyToOne(targetEntity="Common\Model\Address")
     * @ORM\JoinColumn(name="addressId", referencedColumnName="id")
     */
    private $addressId;

    /**
     * @var int
     * @ORM\Column(name="order_status", type="integer")
     */
    private $orderStatus;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
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
     * Set customerId
     * @param integer $customerId     
     * @return ProductOrder
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * Get customerId
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set orderedDate
     * @param \DateTime $orderedDate
     * @return ProductOrder
     */
    public function setOrderedDate($orderedDate)
    {
        $this->orderedDate = $orderedDate;
        return $this;
    }

    /**
     * Get orderedDate
     * @return \DateTime
     */
    public function getOrderedDate()
    {
        return $this->orderedDate;
    }

    /**
     * Set deliveryAddress
     * @param integer $deliveryAddress
     * @return ProductOrder
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * Get deliveryAddress
     * @return integer
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Set orderStatus
     * @param integer $orderStatus
     * @return ProductOrder
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     * @return int
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set createdAt
     * @param \DateTime $createdAt
     * @return ProductOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     * @param \DateTime $updatedAt
     * @return ProductOrder
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


<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Customer_plan
 *
 * @ORM\Table(name="customer_plan")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\Customer_planRepository")
 */
class Customer_plan
{
    const NONPRIME = 1;
    
    const DEFAULT_CUSTOMER_PLAN=1; //1 = NON-PRIME

    CONST PRIME= 2;

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
     * @ORM\Column(name="Customer_plan_name", type="string", length=50,unique=true)
     */
    private $customerPlanName;

    /**
     * @var int
     *
     * @ORM\Column(name="delivery_charge", type="integer")
     */
    private $deliveryCharge;

    /**
     * @var int
     *
     * @ORM\Column(name="validity", type="integer")
     */
    private $validity;

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
        // we set up "created"+"modified"
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
     * Set customerPlanName
     *
     * @param string $customerPlanName
     *
     * @return Customer_plan
     */
    public function setCustomerPlanName($customerPlanName)
    {
        $this->customerPlanName = $customerPlanName;

        return $this;
    }

    /**
     * Get customerPlanName
     *
     * @return string
     */
    public function getCustomerPlanName()
    {
        return $this->customerPlanName;
    }

    /**
     * Set deliveryCharge
     *
     * @param integer $deliveryCharge
     *
     * @return Customer_plan
     */
    public function setDeliveryCharge($deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;

        return $this;
    }

    /**
     * Get deliveryCharge
     *
     * @return int
     */
    public function getDeliveryCharge()
    {
        return $this->deliveryCharge;
    }

    /**
     * Set validity
     *
     * @param integer $validity
     *
     * @return Customer_plan
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return int
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return Customer_plan
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
     * @return Customer_plan
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


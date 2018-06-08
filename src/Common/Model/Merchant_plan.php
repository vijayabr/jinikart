<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Merchant_plan
 *
 * @ORM\Table(name="merchant_plan")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\Merchant_planRepository")
 */
class Merchant_plan
{
    
    const DEFAULT_MERCHANT_PLAN =1; //1 = Silver Plan
    const GOLD_PLAN =2;
    const SILVER_PLAN=3;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer",unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_plan_name", type="string", length=50,unique=true)
     */
    private $merchantPlanName;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="upper_limit", type="float")
     */
    private $upperLimit;

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
     * Set merchantPlanName
     *
     * @param string $merchantPlanName
     *
     * @return Merchant_plan
     */
    public function setMerchantPlanName($merchantPlanName)
    {
        $this->merchantPlanName = $merchantPlanName;

        return $this;
    }

    /**
     * Get merchantPlanName
     *
     * @return string
     */
    public function getMerchantPlanName()
    {
        return $this->merchantPlanName;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Merchant_plan
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set upperLimit
     *
     * @param float $upperLimit
     *
     * @return Merchant_plan
     */
    public function setUpperLimit($upperLimit)
    {
        $this->upperLimit = $upperLimit;

        return $this;
    }

    /**
     * Get upperLimit
     *
     * @return float
     */
    public function getUpperLimit()
    {
        return $this->upperLimit;
    }

    /**
     * Set validity
     *
     * @param integer $validity
     *
     * @return Merchant_plan
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
     * @param \DateTime $createdAt
     *
     * @return Merchant_plan
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
     * @return Merchant_plan
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


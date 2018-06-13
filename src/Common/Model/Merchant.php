<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\BigIntType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Merchant
 *
 * @ORM\Table(name="merchant")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\MerchantRepository")
 */
class Merchant implements UserInterface
{
    const INACTIVE=0;
    const ACTIVE=1;
    const SUSPENDED=2;
    const ROLE="ROLE_MERCHANT";
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
     *@Assert\NotBlank()
     *@Assert\Regex("/^[a-z A-Z]+$/", message="Company name should only contain  alphabets")
     * @ORM\Column(name="company_name", type="string", length=50)
     */
    private $companyName;

    /**
     * @var int
     *one customer has one default address
     * @ORM\ManyToOne(targetEntity="Common\Model\Address",cascade={"persist"})
     * @ORM\JoinColumn(name="addressId", referencedColumnName="id")
     */
    private $addressId;

    /**
     * @var string
     *@Assert\NotBlank(message="Enter Contact Person Name")
     *@Assert\Regex("/^[a-z A-Z]+$/", message="Name should only contain  alphabets")
     * @ORM\Column(name="contact_person_name", type="string", length=50)
     */
    private $contactPersonName;
    
    /**
     *@var BigIntType
     *@Assert\Regex("/^\d{10}$/", message="Mobile number should be 10 digits")
     *@ORM\Column(name="mobile_no", type="bigint", length=13,unique=true)
     */
    private $mobileNo;
    /**
     * @var string
     *@Assert\NotBlank()
     *@Assert\Email(
     *     message="The email is not valid")
     * @ORM\Column(name="email", type="string", length=50,unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    
    /**
     * @var string
     * @ORM\Column(name="company_logo", type="string", length=50,nullable=true)
     */
    private $companyLogo;

    /**
     * @var int
     *
     * @ORM\Column(name="merchant_status", type="smallint")
     */
    private $merchantStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_role", type="string", length=20)
     */
    private $merchantRole;

    /**
     * @var int
     *one customer has one plan
     * @ORM\ManyToOne(targetEntity="Common\Model\Merchant_plan")
     * @ORM\JoinColumn(name="merchantPlanId", referencedColumnName="id")
     */
    private $merchantPlanId;

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


    public function __construct()
    {

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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Merchant
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set addressId
     *
     * @param integer $addressId
     *
     * @return Merchant
     */
    public function setAddressId($addressId)
    {
        $this->addressId = $addressId;

        return $this;
    }

    /**
     * Get addressId
     *
     * @return int
     */
    public function getAddressId()
    {
        return $this->addressId;
    }

    /**
     * Set contactPersonName
     *
     * @param string $contactPersonName
     *
     * @return Merchant
     */
    public function setContactPersonName($contactPersonName)
    {
        $this->contactPersonName = $contactPersonName;

        return $this;
    }

    /**
     * Get contactPersonName
     *
     * @return string
     */
    public function getContactPersonName()
    {
        return $this->contactPersonName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Merchant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Merchant
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set companyLogo
     *
     * @param string $companyLogo
     *
     * @return Merchant
     */
    public function setCompanyLogo($companyLogo)
    {
        $this->companyLogo = $companyLogo;

        return $this;
    }

    /**
     * Get companyLogo
     *
     * @return string
     */
    public function getCompanyLogo()
    {
        return $this->companyLogo;
    }
    /**
     * Set mobileNo
     *
     * @param string $mobileNo
     *
     * @return Customer
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;
        
        return $this;
    }
    
    /**
     * Get mobileNo
     *
     * @return string
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
    }
    
    /**
     * Set merchantStatus
     *
     * @param integer $merchantStatus
     *
     * @return Merchant
     */
    public function setMerchantStatus($merchantStatus)
    {
        $this->merchantStatus = $merchantStatus;

        return $this;
    }

    /**
     * Get merchantStatus
     *
     * @return int
     */
    public function getMerchantStatus()
    {
        return $this->merchantStatus;
    }

    /**
     * Set merchantRole
     *
     * @param string $merchantRole
     *
     * @return Merchant
     */
    public function setMerchantRole($merchantRole)
    {
        $this->merchantRole = $merchantRole;

        return $this;
    }

    /**
     * Get merchantRole
     *
     * @return string
     */
    public function getMerchantRole()
    {
        return $this->merchantRole;
    }

    /**
     * Set merchantPlanId
     *
     * @param integer $merchantPlanId
     *
     * @return Merchant
     */
    public function setMerchantPlanId($merchantPlanId)
    {
        $this->merchantPlanId = $merchantPlanId;

        return $this;
    }

    /**
     * Get merchantPlanId
     *
     * @return int
     */
    public function getMerchantPlanId()
    {
        return $this->merchantPlanId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Merchant
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
     * @return Merchant
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
    public function getRoles()
    {
        return array('ROLE_MERCHANT');
        
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function getUsername()
    {
        return $this->email;
    }
    
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    
}


<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\CustomerRepository")
 */
class Customer implements  UserInterface
{
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
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-z A-Z]+$/", message="First name should only contain  alphabets")
     * @ORM\Column(name="fname", type="string", length=50)
     */
    private $fname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex("/^[A-Z a-z]+$/", message="Last name should only contain  alphabets")
     * @ORM\Column(name="lname", type="string", length=50)
     */
    private $lname;


    /**
     * @var int
     *one customer has one default address
     * @ORM\OneToOne(targetEntity="Common\Model\Address")
     * @ORM\JoinColumn(name="addressId", referencedColumnName="id")
     */
    private $addressId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message="the email is not valid email"
     * )
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * @Assert\Regex("/^\d{10}$/", message="mobile number should be 10 digits")
     * @var string
     * @ORM\Column(name="mobile_no", type="string", length=15,unique=true)
     */
    private $mobileNo;

    /**
     * @var string
     * @Assert\NotBlank(message="please,upload the image")
     * @ORM\Column(name="profile_photo", type="string", length=50)
     */
    private $profilePhoto;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_role", type="string", length=20)
     */
    private $customerRole;

    /**
     * @var int
     * @ORM\Column(name="customer_status", type="smallint")
     */
    private $customerStatus;

    /**
     * @var int
     *one customer has one plan
     * @ORM\ManyToOne(targetEntity="Common\Model\Customer_plan")
     * @ORM\JoinColumn(name="customerPlanId", referencedColumnName="id")
     */

    private $customerPlanId;

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

//        $this->customerPlanId=1;
        $this->customerStatus=1;
//        $this->addressId=1;
        $this->customerRole="ROLE_CUSTOMER";
<<<<<<< Updated upstream
        $this->setUpdatedAt(new \DateTime());
        $this->setCreatedAt(new \DateTime());
    }
=======
       $this->setCreatedAt(new \DateTime());
            if ($this->getUpdatedAt() == null) {
                $this->setUpdatedAt(new \DateTime());
            }
     }
        
>>>>>>> Stashed changes

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
     * Set fname
     *
     * @param string $fname
     *
     * @return Customer
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return Customer
     */
    public function setLname($lname)
    {
        $this->lname = $lname;

        return $this;
    }

    /**
     * Get lname
     *
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * Set addressId
     *
     * @param integer $addressId
     *
     * @return Customer
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
     * Set email
     *
     * @param string $email
     *
     * @return Customer
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
     * Set profilePhoto
     *
     * @param string $profilePhoto
     *
     * @return Customer
     */
    public function setProfilePhoto($profilePhoto)
    {
        $this->profilePhoto = $profilePhoto;

        return $this;
    }

    /**
     * Get profilePhoto
     *
     * @return string
     */
    public function getProfilePhoto()
    {
        return $this->profilePhoto;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Customer
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
     * Set customerRole
     *
     * @param string $customerRole
     *
     * @return Customer
     */
    public function setCustomerRole($customerRole)
    {
        $this->customerRole = $customerRole;

        return $this;
    }

    /**
     * Get customerRole
     *
     * @return string
     */
    public function getCustomerRole()
    {
        return $this->customerRole;
    }

    /**
     * Set customerStatus
     *
     * @param integer $customerStatus
     *
     * @return Customer
     */
    public function setCustomerStatus($customerStatus)
    {
        $this->customerStatus = $customerStatus;

        return $this;
    }

    /**
     * Get customerStatus
     *
     * @return int
     */
    public function getCustomerStatus()
    {
        return $this->customerStatus;
    }

    /**
     * Set customerPlanId
     *
     * @param integer $customerPlanId
     *
     * @return Customer
     */
    public function setCustomerPlanId($customerPlanId)
    {
        $this->customerPlanId = $customerPlanId;

        return $this;
    }

    /**
     * Get customerPlanId
     *
     * @return int
     */
    public function getCustomerPlanId()
    {
        return $this->customerPlanId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Customer
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
     * @return Customer
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
        return array('ROLE_CUSTOMER');

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


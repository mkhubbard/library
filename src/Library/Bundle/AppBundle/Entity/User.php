<?php

namespace Library\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Doctrine\ORM\Mapping\OneToMany;
//use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Class
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Library\Bundle\AppBundle\Entity\UserRepository")
 * @UniqueEntity(
 *      fields = "email",
 *      message = "email_already_used"
 * )
 */
class User implements AdvancedUserInterface, EquatableInterface, \Serializable
{

    /**
     * @var integer User account primary ID.
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string User account name.
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "username_min_length",
     *      maxMessage = "username_max_length"
     * )
     */
    private $username;

    /**
     * @var string Account password.
     * @ORM\Column(name="password", type="string", length=60)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      max = 30,
     *      minMessage = "password_min_length",
     *      maxMessage = "password_max_length"
     * )
     */
    private $password;

    /**
     * @var string Account email address
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "email_max_length"
     * )
     * @Assert\Email(
     *      message = "email_not_valid",
     *      checkMX = false
     * )
     */
    private $email;

    /**
     * @var string Symfony security role.
     * @ORM\Column(name="role", type="string", length=15)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "role_max_length"
     * )
     */
    private $role;

    /**
     * @var boolean Tells if the account is active.
     * @ORM\Column(name="is_active", type="boolean")
     * @Assert\Type(type="bool", message="type_not_valid")
     */
    private $isActive;

    /**
     * @OneToMany(targetEntity="UserBook", mappedBy="user")
     */
    //private $books;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->role = 'ROLE_USER';
        //$this->books = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = trim($username);
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // The bcrypt encoder requires null to be returned as it will
        // generate the salt automatically.
        return null;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set the email address for account.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    /**
     * Retrieve the email address for this account.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }


    /**
     * @param $role
     * @throws \InvalidArgumentException
     */
    public function setRole($role)
    {
        $role = strtoupper($role);
        if (!in_array($role, array('ROLE_ADMIN', 'ROLE_USER'))) {
            throw new \InvalidArgumentException('Attempted to set invalid role for user "' . $role . "'");
        }

        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // Not required.
    }

    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user)
    {
        return ($this->username === $user->getUsername());
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username
        ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo(UserInterface $user)
    {
        $result = ($user->getId() == $this->id);
        $result = $result && ($user->getUsername() == $this->username);

        return $result;
    }

}

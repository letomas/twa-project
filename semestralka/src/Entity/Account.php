<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ExclusionPolicy("all")
 */
class Account implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $lastName;

    /**
     * @ORM\Column(type="array", length=255)
     * @Expose
     */
    private $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="Request", mappedBy="attendees")
     * @ORM\JoinTable(name="request_attendee",
     *     joinColumns={@ORM\JoinColumn(name="attendee_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="request_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $requestsAttendees;

    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="createBy")
     * @MaxDepth(1)
     */
    private $requestAuthor;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="manageBy")
     * @MaxDepth(1)
     */
    private $roomsManager;

    /**
     * @ORM\ManyToMany(targetEntity="Room", inversedBy="occupyByUsers")
     * @ORM\JoinTable(name="room_occupy",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="room_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $roomOccupy;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="manageBy")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $clubManager;

    /**
     * @ORM\ManyToMany(targetEntity="Club", mappedBy="members")
     * @ORM\JoinTable(name="club_member",
     *      joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="club_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $clubs = [];

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getRequestsAttendees()
    {
        return $this->requestsAttendees;
    }

    /**
     * @param mixed $requestsAttendees
     */
    public function setRequestsAttendees($requestsAttendees): void
    {
        $this->requestsAttendees = $requestsAttendees;
    }

    /**
     * @return mixed
     */
    public function getRequestAuthor()
    {
        return $this->requestAuthor;
    }

    /**
     * @param mixed $requestAuthor
     */
    public function setRequestAuthor($requestAuthor): void
    {
        $this->requestAuthor = $requestAuthor;
    }

    /**
     * @return mixed
     */
    public function getRoomsManager()
    {
        return $this->roomsManager;
    }

    /**
     * @param mixed $roomsManager
     */
    public function setRoomsManager($roomsManager): void
    {
        $this->roomsManager = $roomsManager;
    }

    /**
     * @return mixed
     */
    public function getRoomOccupy()
    {
        return $this->roomOccupy;
    }

    /**
     * @param mixed $roomOccupy
     */
    public function setRoomOccupy($roomOccupy): void
    {
        $this->roomOccupy = $roomOccupy;
    }

    /**
     * @return mixed
     */
    public function getClubManager()
    {
        return $this->clubManager;
    }

    /**
     * @param mixed $clubManager
     */
    public function setClubManager($clubManager): void
    {
        $this->clubManager = $clubManager;
    }

    /**
     * @return array
     */
    public function getClubs(): array
    {
        return $this->clubs;
    }

    /**
     * @param array $clubs
     */
    public function setClubs(array $clubs): void
    {
        $this->clubs = $clubs;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}

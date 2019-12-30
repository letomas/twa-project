<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

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
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="manageBy")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $groupManager;

    /**
     * @ORM\ManyToMany(targetEntity="Group", mappedBy="groupMembers")
     * @ORM\JoinTable(name="group_member",
     *      joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $groups = [];

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
    public function getGroupManager()
    {
        return $this->groupManager;
    }

    /**
     * @param mixed $groupManager
     */
    public function setGroupManager($groupManager): void
    {
        $this->groupManager = $groupManager;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}

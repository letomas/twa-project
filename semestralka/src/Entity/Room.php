<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\ManyToOne(fetch="LAZY", targetEntity="Building", inversedBy="rooms")
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $building = [];

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="roomsManager")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $manageBy;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roomOccupy")
     * @ORM\JoinTable(name="room_occupy",
     *      joinColumns={@ORM\JoinColumn(name="room_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $occupyByUsers = [];

    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="room")
     * @MaxDepth(1)
     */
    private $requests = [];

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="rooms", fetch="LAZY")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $group;

    /**
     * @return mixed
     */
    public function getManageBy()
    {
        return $this->manageBy;
    }

    /**
     * @param mixed $manageBy
     */
    public function setManageBy($manageBy): void
    {
        $this->manageBy = $manageBy;
    }

    /**
     * @return array
     */
    public function getOccupyByUsers(): array
    {
        return $this->occupyByUsers;
    }

    /**
     * @param array $occupyByUsers
     */
    public function setOccupyByUsers(array $occupyByUsers): void
    {
        $this->occupyByUsers = $occupyByUsers;
    }

    /**
     * @return array
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * @param array $requests
     */
    public function setRequests(array $requests): void
    {
        $this->requests = $requests;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): void
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building): void
    {
        $this->building = $building;
    }
}
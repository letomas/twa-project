<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="ClubRepository")
 * @ExclusionPolicy("none")
 */
class Club
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="groupManager", cascade={"persist"})
     * @MaxDepth(1)
     */
    private $manageBy;

    /**
     * @ORM\ManyToMany(targetEntity="Club", inversedBy="superGroup")
     * @ORM\JoinTable(name = "group_subgroup",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="sub_group_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $subGroup = [];

    /**
     * @ORM\ManyToMany(targetEntity="Club", mappedBy="subGroup")
     * @MaxDepth(1)
     */
    private $superGroup;

    /**
     * @ORM\ManyToMany(targetEntity="Account", inversedBy="groups")
     * @ORM\JoinTable(name="group_member",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $members = [];

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="group")
     * @MaxDepth(1)
     */
    private $rooms = [];

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
    public function getSubGroup(): array
    {
        return $this->subGroup;
    }

    /**
     * @param array $subGroup
     */
    public function setSubGroup(array $subGroup): void
    {
        $this->subGroup = $subGroup;
    }

    /**
     * @return mixed
     */
    public function getSuperGroup()
    {
        return $this->superGroup;
    }

    /**
     * @param mixed $superGroup
     */
    public function setSuperGroup($superGroup): void
    {
        $this->superGroup = $superGroup;
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }

    /**
     * @return array
     */
    public function getRooms(): array
    {
        return $this->rooms;
    }

    /**
     * @param array $rooms
     */
    public function setRooms(array $rooms): void
    {
        $this->rooms = $rooms;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

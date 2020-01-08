<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
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
     * @ORM\OneToMany(targetEntity="Account", mappedBy="clubManager", cascade={"persist"})
     * @MaxDepth(1)
     */
    private $manageBy = [];

    /**
     * @ORM\ManyToMany(targetEntity="Club", inversedBy="superClub")
     * @ORM\JoinTable(name = "club_subclub",
     *     joinColumns={@ORM\JoinColumn(name="club_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="sub_club_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $subClubs = [];

    /**
     * @ORM\ManyToMany(targetEntity="Club", mappedBy="subClub")
     * @MaxDepth(1)
     */
    private $superClub;

    /**
     * @ORM\ManyToMany(targetEntity="Account", inversedBy="clubs")
     * @ORM\JoinTable(name="club_member",
     *      joinColumns={@ORM\JoinColumn(name="club_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $members = [];

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="club")
     * @MaxDepth(1)
     */
    private $rooms = [];

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
     * @return array
     */
    public function getManageBy(): array
    {
        return $this->manageBy;
    }

    /**
     * @param array $manageBy
     */
    public function setManageBy(array $manageBy): void
    {
        $this->manageBy = $manageBy;
    }

    /**
     * @return array
     */
    public function getSubClubs(): array
    {
        return $this->subClubs;
    }

    /**
     * @param array $subClubs
     */
    public function setSubClubs(array $subClubs): void
    {
        $this->subClubs = $subClubs;
    }

    /**
     * @return mixed
     */
    public function getSuperClub()
    {
        return $this->superClub;
    }

    /**
     * @param mixed $superClub
     */
    public function setSuperClub($superClub): void
    {
        $this->superClub = $superClub;
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
}

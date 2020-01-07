<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 * @ExclusionPolicy("none")
 */
class Request
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $createTime;

    /**
     * @ORM\Column(type="time")
     */
    private $start;

    /**
     * @ORM\Column(type="time")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="requestAuthor")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $createBy;

    /**
     * @ORM\ManyToMany(targetEntity="Account", inversedBy="requestsAttendees")
     * @ORM\JoinTable(name="request_attendee",
     *      joinColumns={@ORM\JoinColumn(name="request_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="attendee_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     */
    private $attendees;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="requests")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     * @MaxDepth(1)
     */
    private $room;

    /**
     * @return mixed
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * @param mixed $createBy
     */
    public function setCreateBy($createBy): void
    {
        $this->createBy = $createBy;
    }

    /**
     * @return mixed
     */
    public function getAttendees()
    {
        return $this->attendees;
    }

    /**
     * @param mixed $attendees
     */
    public function setAttendees($attendees): void
    {
        $this->attendees = $attendees;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room): void
    {
        $this->room = $room;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setCreateTime(\DateTimeInterface $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }
}

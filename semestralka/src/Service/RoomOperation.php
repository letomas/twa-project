<?php


namespace App\Service;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RoomOperation
 * @package App\Service
 */
class RoomOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * RoomOperation constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Room $room
     */
    public function save(Room $room)
    {
        $this->em->persist($room);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Room $room
     */
    public function remove(Room $room)
    {
        $this->em->remove($room);
        $this->em->flush();
    }
}
<?php


namespace App\Service;

use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ClubOperation
 * @package App\Service
 */
class ClubOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * ClubOperation constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Club $club
     */
    public function save(Club $club)
    {
        $this->em->persist($club);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Club $club
     */
    public function remove(Club $club)
    {
        $this->em->remove($club);
        $this->em->flush();
    }

    public function addAccount(Club $club, Account $account)
    {
        $members = $club->getMembers();
        if(in_array($account, $members, true)) {
            return;
        }
        $members[] = $account;
        $clubs = $account->getClubs();
        $clubs[] = $club;

        $club->setMembers($members);
        $account->setClubs($clubs);

        $this->em->flush();
    }

    public function removeAccount(Club $club, Account $account)
    {
        $members = $club->getMembers();
        if(!in_array($account, $members, true)) {
            return;
        }
        $clubs = $account->getClubs();

        $members = $this->removeObjectFromArray($members, $account);
        $clubs = $this->removeObjectFromArray($clubs, $club);

        $club->setMembers($members);
        $account->setClubs($clubs);

        $this->em->flush();
    }

    public function addRoom(Club $club, Room $room)
    {
        $rooms = $club->getRooms();
        if(in_array($room, $rooms, TRUE)) {
            return;
        }
        $rooms[] = $room;

        $club->setRooms($rooms);
        $room->setClub($club);

        $this->em->flush();
    }

    public function removeRoom(Club $club, Room $room)
    {
        $rooms = $club->getRooms();
        if(!in_array($room, $rooms, TRUE)) {
            return;
        }

        $this->removeObjectFromArray($rooms, $room);

        $club->setRooms($rooms);
        $room->setClub(null);

        $this->em->flush();
    }

    public function removeObjectFromArray(array $array, $value)
    {
        $key = array_search($value, $array, TRUE);
        if($key) {
            unset($array[$key]);
        }

        return $array;
    }
}
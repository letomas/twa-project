<?php


namespace App\Service;

use App\Entity\Account;
use App\Entity\Group;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use function Sodium\add;

/**
 * Class GroupOperation
 * @package App\Service
 */
class GroupOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * GroupOperation constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Group $group
     */
    public function save(Group $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Group $group
     */
    public function remove(Group $group)
    {
        $this->em->remove($group);
        $this->em->flush();
    }

    public function addAccount(Group $group, Account $account)
    {
        $members = $group->getMembers();
        if(in_array($account, $members, true)) {
            return;
        }
        $members[] = $account;
        $groups = $account->getGroups();
        $groups[] = $group;

        $group->setMembers($members);
        $account->setGroups($groups);

        $this->em->flush();
    }

    public function removeAccount(Group $group, Account $account)
    {
        $members = $group->getMembers();
        if(!in_array($account, $members, true)) {
            return;
        }
        $groups = $account->getGroups();

        $members = $this->removeObjectFromArray($members, $account);
        $groups = $this->removeObjectFromArray($groups, $group);

        $group->setMembers($members);
        $account->setGroups($groups);

        $this->em->flush();
    }

    public function addRoom(Group $group, Room $room)
    {
        $rooms = $group->getRooms();
        if(in_array($room, $rooms, TRUE)) {
            return;
        }
        $rooms[] = $room;

        $group->setRooms($rooms);
        $room->setGroup($group);

        $this->em->flush();
    }

    public function removeRoom(Group $group, Room $room)
    {
        $rooms = $group->getRooms();
        if(!in_array($room, $rooms, TRUE)) {
            return;
        }

        $this->removeObjectFromArray($rooms, $room);

        $group->setRooms($rooms);
        $room->setGroup(null);

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
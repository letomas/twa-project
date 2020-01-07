<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Room;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class RoomVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ADD = 'add';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::ADD])) {
            return false;
        }

        if (!$subject instanceof Account) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')){
            return true;
        }

        $account = $token->getUser();

        $room = $subject;

        if (!$account instanceof Account) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($room);
            case self::EDIT:
                return $this->canEdit($account, $room);
            case self::DELETE:
                return $this->canDelete();
            case self::ADD:
                return $this->canAdd();
        }

        throw new \LogicException('This code should not be reached!');
    }

    // pokud je mistnost verejna muzou se podivat vsichni, jinak musi byt uzivatel
    private function canView(Room $room)
    {
        if ($room->getType() == 'public'){
            return true;
        }

        return $this->security->isGranted('ROLE_USER');
    }

    // jenom kdyz je groupAdmin danne mistnosti
    private function canEdit(Account $account, Room $room)
    {
        return $this->isRoomClubAdmin($account, $room);
    }

    // pouze super admin
    private function canDelete()
    {
        return false;
    }

    // pouze super admin
    private function canAdd()
    {
        return false;
    }

    private function isRoomClubAdmin(Account $account, Room $room)
    {
        $roomClub = $room->getClub();
        $groupManagedByAccount = $account->getClubManager();

        if(!$groupManagedByAccount) {
            return false;
        }

        if($roomClub === $groupManagedByAccount) {
            return true;
        }

        return $this->subgroupsContainClub($groupManagedByAccount->getSubClub(), $roomClub);
    }

    private function subgroupsContainClub(Club $subgroups, Club $group) {
        foreach ($subgroups as $subgroup) {
            if($subgroup === $group) {
                return true;
            }
        }

        return false;
    }
}
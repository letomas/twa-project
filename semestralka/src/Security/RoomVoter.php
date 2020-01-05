<?php

namespace App\Security;

use App\Entity\Account;
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
                return $this->canView($account, $room);
            case self::EDIT:
                return $this->canEdit($account, $room);
            case self::DELETE:
                return $this->canDelete($account, $room);
            case self::ADD:
                return $this->canAdd($account, $room);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function isGroupAdminRoom(Account $account, $room)
    {
        // mistnosti jejiz patri do skupiny a jsem v te skupine
        foreach ($account->getGroups() as $group){
            // jestli patri mistnost skupine
            foreach ($group->getRooms() as $groupRoom){
                if ($groupRoom === $room){
                    return true;
                }
            }

            // jestli patri nejake podskupine
            foreach ($group->getSubGroup() as $subGroup){
                foreach ($subGroup->getRooms() as $groupRoom) {
                    if ($groupRoom === $room){
                        return true;
                    }
                }
            }
        }

        return false;
    }

    // pokud je mistnost verejna muzou se podivat vsichni, jinak musi byt uzivatel
    private function canView(Account $account, Room $room)
    {
        if ($room->getType() == 'public'){
            return true;
        }

        return $this->security->isGranted('ROLE_USER');
    }

    // jenom kdyz je groupAdmin danne mistnosti
    private function canEdit(Account $account, Room $room)
    {
        return $this->isGroupAdminRoom($account, $room);
    }

    // pouze super admin
    private function canDelete(Account $account, Room $room)
    {
        return false;
    }

    // pouze super admin
    private function canAdd(Account $account, Room $room)
    {
        return false;
    }
}
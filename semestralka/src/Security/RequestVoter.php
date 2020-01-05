<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Request;
use App\Entity\Room;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class RequestVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ADD = 'add';
    const APPROVE = 'approve';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::ADD, self::APPROVE])) {
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

        $request = $subject;

        if (!$account instanceof Account) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($account, $request);
            case self::EDIT:
                return $this->canEdit($account, $request);
            case self::DELETE:
                return $this->canDelete($account, $request);
            case self::ADD:
                return $this->canAdd($account, $request);
            case self::APPROVE:
                return $this->canApprove($account, $request);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Account $account, Request $request)
    {
        // vsichni co jsou v seznamu mohou se podivat na request

        foreach ($request->getAttendees() as $attendee){
            if ($attendee->getId() === $account->getId()){
                return true;
            }
        }

        return $this->canEdit($account, $request);
    }

    private function isRoomAdmin(Account $account, $roomId)
    {
        // mistnosti jejiz jsem spravcem
        foreach ($account->getRoomsManager() as $room){
            if ($room->getId() === $roomId){
                return true;
            }
        }

        return false;
    }

    private function isGroupMember(Account $account, $roomId)
    {
        foreach ($account->getGroups() as $group){
            foreach ($group->getRooms() as $room){
                if ($room->getId() === $roomId){
                    return true;
                }
            }
        }
    }

    private function isGroupAdminRoom(Account $account, $roomId)
    {
        // mistnosti jejiz patri do skupiny a jsem v te skupine
        foreach ($account->getRoomsManager() as $group){
            // jestli patri mistnost skupine
            foreach ($account->getGroups() as $group){
                foreach ($group->getRooms() as $room){
                    if ($room->getId() === $roomId){
                        return true;
                    }
                }
            }

            // jestli patri nejake podskupine
            foreach ($group->getSubGroup() as $subGroup){
                foreach ($subGroup->getRooms() as $room) {
                    if ($room->getId() === $roomId){
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function canEdit(Account $account, Request $request)
    {
        if ($this->isRoomAdmin($account, $request->getRoom()->getId()))
        {
            return true;
        }

        if ($this->isGroupAdminRoom($account, $request->getRoom()->getId())){
            return true;
        }

        return false;
    }

    // pouze super admin
    private function canDelete(Account $account, Request $request)
    {
        return false;
    }

    private function canAdd(Account $account, Request $request)
    {
        $roomId = $request->getRoom()->getId();
        // mistnosti jejiz jsem uzivatelem
        foreach ($account->getRoomOccupy() as $room){
            if ($room->getId() === $roomId){
                return true;
            }
        }

        if ($this->isGroupMember($account, $roomId))
        {
            return true;
        }

        if ($this->isRoomAdmin($account, $roomId))
        {
            return true;
        }

        if ($this->isGroupAdminRoom($account, $roomId)){
            return true;
        }

        return false;
    }

    // muze menit tak muze i schvalit
    private function canApprove(Account $account, $request)
    {
        return $this->canEdit($account, $request);
    }
}
<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Group;
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
                return $this->canDelete();
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
            if ($attendee === $account){
                return true;
            }
        }

        return $this->canEdit($account, $request);
    }

    private function canEdit(Account $account, Request $request)
    {
        $room = $request->getRoom();
        if ($this->isRoomAdmin($account, $room))
        {
            return true;
        }

        if ($this->isRoomGroupAdmin($account, $room)){
            return true;
        }

        return false;
    }

    // pouze super admin
    private function canDelete()
    {
        return false;
    }

    private function canAdd(Account $account, Request $request)
    {
        $room = $request->getRoom();
        // mistnosti jejiz jsem uzivatelem
        foreach ($account->getRoomOccupy() as $occupiedRoom){
            if ($occupiedRoom === $room){
                return true;
            }
        }

        if ($this->isGroupMember($account, $room))
        {
            return true;
        }

        if ($this->isRoomAdmin($account, $room))
        {
            return true;
        }

        if ($this->isRoomGroupAdmin($account, $room)){
            return true;
        }

        return false;
    }

    // muze menit tak muze i schvalit
    private function canApprove(Account $account, $request)
    {
        return $this->canEdit($account, $request);
    }

    private function isRoomAdmin(Account $account, Room $room)
    {
        // mistnosti jejiz jsem spravcem
        foreach ($account->getRoomsManager() as $managedRoom){
            if ($managedRoom === $room){
                return true;
            }
        }

        return false;
    }

    private function isGroupMember(Account $account, Room $room)
    {
        foreach ($account->getGroups() as $group){
            foreach ($group->getRooms() as $groupRoom){
                if ($groupRoom === $room){
                    return true;
                }
            }
        }

        return false;
    }

    private function isRoomGroupAdmin(Account $account, Room $room)
    {
        $roomGroup = $room->getGroup();
        $groupManagedByAccount = $account->getGroupManager();

        if(!$groupManagedByAccount) {
            return false;
        }

        if($roomGroup === $groupManagedByAccount) {
            return true;
        }

        return $this->subgroupsContainGroup($groupManagedByAccount->getSubGroup(), $roomGroup);
    }

    private function subgroupsContainGroup(Group $subgroups, Group $group) {
        foreach ($subgroups as $subgroup) {
            if($subgroup === $group) {
                return true;
            }
        }

        return false;
    }
}
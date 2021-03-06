<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Club;
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
        return ($this->isRoomAdmin($account, $room) || $this->isRoomGroupAdmin($account, $room));
    }

    // pouze super admin
    private function canDelete()
    {
        return false;
    }

    private function canAdd(Account $account, Request $request)
    {
        $room = $request->getRoom();
        $club = $room->getGroup();
        // mistnosti jejiz jsem uzivatelem
        foreach ($account->getRoomOccupy() as $occupiedRoom){
            if ($occupiedRoom === $room){
                return true;
            }
        }

        return ($this->isGroupMember($account, $club)
            || $this->isRoomAdmin($account, $room)
            || $this->isRoomGroupAdmin($account, $room));
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

    private function isGroupMember(Account $account, Club $club)
    {
        foreach ($account->getClubs() as $userGroup){
            if($club === $userGroup) {
                return true;
            }
        }

        return false;
    }

    private function isRoomGroupAdmin(Account $account, Room $room)
    {
        $roomGroup = $room->getClub();
        $clubManagedByAccount = $account->getClubManager();

        if(!$clubManagedByAccount) {
            return false;
        }

        if($roomGroup === $clubManagedByAccount) {
            return true;
        }

        return $this->subclubsContainGroup($clubManagedByAccount->getSubGroup(), $roomGroup);
    }

    private function subclubsContainGroup(Club $subclubs, Club $club) {
        foreach ($subclubs as $subclub) {
            if($subclub === $club) {
                return true;
            }
        }

        return false;
    }
}
<?php

namespace App\Security;

use App\Entity\Account;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class EmployeeVoter extends Voter
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

        if (!$account instanceof Account) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($account, $subject);
            case self::EDIT:
                return $this->canEdit($account, $subject);
            case self::DELETE:
                return $this->canDelete($account, $subject);
            case self::ADD:
                return $this->canAdd($account, $subject);
        }

        throw new \LogicException('This code should not be reached!');
    }


    private function canView(Account $account, int $id)
    {
        // if they can edit, they can view
        if ($this->canEdit($account, $id)) {
            return true;
        }

        return false;
    }

    private function canEdit(Account $account, int $id)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object

        if ($account->getId() === $id) {
            return true;
        }

        return false;
    }

    private function canDelete(Account $account, $id)
    {
        if ($account->getId() === $id) {
            return true;
        }

        return false;
    }

    private function canAdd(Account $account, $id)
    {
        if ($account->getId() === $id) {
            return true;
        }

        return false;
    }
}
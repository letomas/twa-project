<?php


namespace App\Controller\Rest;

use App\Entity\Account;
use App\Service\AccountOperation;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;


/**
 * Class AccountController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Account")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class AccountController extends AbstractFOSRestController
{
    /**
     * @var AccountOperation
     */
    protected $accountOperation;

    /**
     * AccountController constructor.
     * @param $accountOperation
     */
    public function __construct(AccountOperation $accountOperation)
    {
        $this->accountOperation = $accountOperation;
    }

    /**
     * @return Account[]|object[]
     */
    public function cgetAction ()
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();
        if(!$accounts) {
            throw $this->createNotFoundException();
        }

        return $accounts;
    }

    /**
     *
     * @param $id
     * @return object|null
     */
    public function getAction($id)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if(!$account) {
            throw $this->createNotFoundException();
        }

        return $account;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRoomsAction($id)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if(!$account) {
            throw $this->createNotFoundException();
        }

        $rooms = $account->getRoomsManager();
        if(!$rooms) {
            throw $this->createNotFoundException();
        }

        return $rooms;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRequestsAction($id) {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if(!$account) {
            throw $this->createNotFoundException();
        }

        $groups = $account->getRequestAuthor();
        if(!$groups) {
            throw $this->createNotFoundException();
        }

        return $groups;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getGroupsAction($id) {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if(!$account) {
            throw $this->createNotFoundException();
        }

        $groups = $account->getRequestAuthor();
        if(!$groups) {
            throw $this->createNotFoundException();
        }

        return $groups;
    }

    /**
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$account) {
            throw $this->createNotFoundException();
        }

        $this->accountOperation->remove($account);
        return $this->redirectView($this->generateUrl('api_get_accounts'));
    }
}
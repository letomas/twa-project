<?php


namespace App\Controller\Rest;

use App\Entity\Account;
use App\Service\AccountOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;


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
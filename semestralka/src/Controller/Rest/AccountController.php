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
 * Class EmployeeController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Account")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class AccountController extends AbstractFOSRestController
{
    protected $accountOperation;

    /**
     * AccountController constructor.
     * @param $accountOperation
     */
    public function __construct($accountOperation)
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
}
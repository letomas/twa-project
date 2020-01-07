<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountCreateType;
use App\Form\AccountSuperAdminType;
use App\Service\AccountOperation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/accounts")
 */
class AccountController extends AbstractController
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
     * @Route("/", name="accounts")
     */
    public function listAction()
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="account_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);

        if ($account === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('account/detail.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * @Route("/create", name="account_create", defaults={"id": null})
     * @Route("/edit/{id}", name="account_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editAction($id, Request $request)
    {
        $account = $id ?
            $this->getDoctrine()->getRepository(Account::class)->find($id) : new account();

        if(!$account) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(AccountCreateType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($id) {
                $this->accountOperation->update();
            } else {
                $this->accountOperation->save($account);
            }

            return $this->redirectToRoute('account_detail', [
                'id' => $account->getId(),
            ]);
        }

        if($id) {
            return $this->render('account/edit.html.twig', [
                'form' => $form->createView(),
                'account' => $account,
            ]);
        } else {
            return $this->render('account/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/remove/{id}", name="account_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);

        if ($account === null)
        {
            throw $this->createNotFoundException();
        }

        $this->accountOperation->remove($account);

        return $this->redirectToRoute('accounts');
    }
}

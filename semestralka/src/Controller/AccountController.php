<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="accounts")
     */
    public function listAction()
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
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
     * @return Response
     */
    public function editAction($id, $request)
    {
        $account = $id ?
            $this->getDoctrine()->getRepository(Account::class)->find($id) : new account();

        if(!$account) {
            throw $this->createNotFoundException();
        }
        /*
            $form = $this->createForm(AccountType::class, $account);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($id) {
                    $em->flush();
                } else {
                    $em->persist($account);
                    $em->flush();
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
        */

        return $this->redirectToRoute('accounts');
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

        $em = $this->getDoctrine()->getManager();
        $em->remove($account);
        $em->flush();

        return $this->redirectToRoute('accounts');
    }
}

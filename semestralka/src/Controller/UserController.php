<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/detail/{id}", name="user_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('user/detail.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/create", name="user_create", defaults={"id": null})
     * @Route("/edit/{id}", name="user_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function editAction($id, $request)
    {
        $user = $id ?
            $this->getDoctrine()->getRepository(User::class)->find($id) : new user();

        if(!$user) {
            throw $this->createNotFoundException();
        }
        /*
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($id) {
                    $em->flush();
                } else {
                    $em->persist($user);
                    $em->flush();
                }

                return $this->redirectToRoute('user_detail', [
                    'id' => $user->getId(),
                ]);
            }

            if($id) {
                return $this->render('user/edit.html.twig', [
                    'form' => $form->createView(),
                    'user' => $user,
                ]);
            } else {
                return $this->render('user/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        */

        return $this->redirectToRoute('users');
    }

    /**
     * @Route("/remove/{id}", name="user_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user === null)
        {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('users');
    }
}

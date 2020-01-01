<?php

namespace App\Controller;

use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 * @package App\Controller
 * @Route("/groups")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="groups")
     */
    public function listAction()
    {
        $groups = $this->getDoctrine()->getRepository(Group::class)->findAll();

        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }

    /**
     * @Route("/detail/{id}", name="group_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);

        if ($group === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('group/detail.html.twig', [
            'group' => $group,
        ]);
    }

    /**
     * @Route("/create", name="group_create", defaults={"id": null})
     * @Route("/edit/{id}", name="group_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function editAction($id, $request)
    {
        $group = $id ?
            $this->getDoctrine()->getRepository(Group::class)->find($id) : new group();

        if(!$group) {
            throw $this->createNotFoundException();
        }
        /*
            $form = $this->createForm(GroupType::class, $group);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($id) {
                    $em->flush();
                } else {
                    $em->persist($group);
                    $em->flush();
                }

                return $this->redirectToRoute('group_detail', [
                    'id' => $group->getId(),
                ]);
            }

            if($id) {
                return $this->render('group/edit.html.twig', [
                    'form' => $form->createView(),
                    'group' => $group,
                ]);
            } else {
                return $this->render('group/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        */

        return $this->redirectToRoute('groups');
    }

    /**
     * @Route("/remove/{id}", name="group_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);

        if ($group === null)
        {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();

        return $this->redirectToRoute('groups');
    }
}

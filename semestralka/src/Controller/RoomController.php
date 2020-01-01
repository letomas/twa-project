<?php

namespace App\Controller;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoomController
 * @package App\Controller
 * @Route("/rooms")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="rooms")
     */
    public function listAction()
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();

        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }

    /**
     * @Route("/detail/{id}", name="room_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if ($room === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('room/detail.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/create", name="room_create", defaults={"id": null})
     * @Route("/edit/{id}", name="room_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function editAction($id, $request)
    {
        $room = $id ?
            $this->getDoctrine()->getRepository(Room::class)->find($id) : new room();

        if(!$room) {
            throw $this->createNotFoundException();
        }
        /*
            $form = $this->createForm(RoomType::class, $room);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($id) {
                    $em->flush();
                } else {
                    $em->persist($room);
                    $em->flush();
                }

                return $this->redirectToRoute('room_detail', [
                    'id' => $room->getId(),
                ]);
            }

            if($id) {
                return $this->render('room/edit.html.twig', [
                    'form' => $form->createView(),
                    'room' => $room,
                ]);
            } else {
                return $this->render('room/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        */

        return $this->redirectToRoute('rooms');
    }

    /**
     * @Route("/remove/{id}", name="room_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if ($room === null)
        {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($room);
        $em->flush();

        return $this->redirectToRoute('rooms');
    }
}

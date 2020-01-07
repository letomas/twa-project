<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Service\RoomOperation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
     * @var
     */
    protected $roomOperation;

    /**
     * RoomController constructor.
     * @param $roomOperation
     */
    public function __construct(RoomOperation $roomOperation)
    {
        $this->roomOperation = $roomOperation;
    }

    /**
     * @Route("/", name="rooms")
     */
    public function listAction()
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();

        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/detail/{id}", methods={"GET"}, name="room_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if ($room === null) {
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
     * @param Request $request
     * @return Response
     */
    public function editAction($id, Request $request)
    {
        $room = $id ?
            $this->getDoctrine()->getRepository(Room::class)->find($id) : new room();

        if(!$room) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(RoomType::class, $room)
            ->add( 'submit', SubmitType::class, ['label' => 'Save']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($id) {
                $this->roomOperation->update();
            } else {
                $this->roomOperation->save($room);
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

        $this->roomOperation->remove($room);

        return $this->redirectToRoute('rooms');
    }
}

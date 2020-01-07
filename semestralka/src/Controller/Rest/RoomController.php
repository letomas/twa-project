<?php


namespace App\Controller\Rest;

use App\Entity\Room;
use App\Service\RoomOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class RoomController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Room")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class RoomController extends AbstractFOSRestController
{
    protected $roomOperation;

    /**
     * RoomController constructor.
     * @param $roomOperation
     */
    public function __construct(RoomOperation $roomOperation)
    {
        $this->roomOperation = $roomOperation;
    }

    public function cgetAction ()
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();
        if(!$rooms) {
            throw $this->createNotFoundException();
        }

        return $rooms;
    }

    /**
     *
     * @param $id
     * @return object|null
     */
    public function getAction($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if(!$room) {
            throw $this->createNotFoundException();
        }

        return $room;
    }
}
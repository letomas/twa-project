<?php


namespace App\Controller\Rest;

use App\Entity\Room;
use App\Service\RoomOperation;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoomController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Room")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class RoomController extends AbstractFOSRestController
{
    /**
     * @var RoomOperation
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
     * @param Request $request
     * @return QueryBuilder
     */
    public function cgetAction (Request $request)
    {
        $filter = $request->query->get('filter');
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAllQueryBuilder($filter);
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

    /**
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$room) {
            throw $this->createNotFoundException();
        }

        $this->roomOperation->remove($room);
        return $this->redirectView($this->generateUrl('api_get_rooms'));
    }
}
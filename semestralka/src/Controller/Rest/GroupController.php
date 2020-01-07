<?php


namespace App\Controller\Rest;

use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Room;
use App\Service\GroupOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;

/**
 * Class GroupController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Club")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class GroupController extends AbstractFOSRestController
{
    /**
     * @var GroupOperation
     */
    protected $groupOperation;

    /**
     * GroupController constructor.
     * @param $groupOperation
     */
    public function __construct(GroupOperation $groupOperation)
    {
        $this->groupOperation = $groupOperation;
    }

    /**
     * @return Club[]|object[]
     */
    public function cgetAction ()
    {
        $groups = $this->getDoctrine()->getRepository(Club::class)->findAll();
        if(!$groups) {
            throw $this->createNotFoundException();
        }

        return $groups;
    }

    /**
     *
     * @param $id
     * @return object|null
     */
    public function getAction($id)
    {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if(!$group) {
            throw $this->createNotFoundException();
        }

        return $group;
    }

    /**
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if (!$group) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->remove($group);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }

    /**
     * @param $id
     * @return array
     */
    public function getAccountsAction($id) {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if (!$group) {
            throw $this->createNotFoundException();
        }

        $members = $group->getMembers();
        if(!$members) {
            throw $this->createNotFoundException();
        }

        return $members;
    }

    /**
     * @param $id
     * @return array
     */
    public function getRoomsAction($id) {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if (!$group) {
            throw $this->createNotFoundException();
        }

        $rooms = $group->getRooms();
        if(!$rooms) {
            throw $this->createNotFoundException();
        }

        return $rooms;
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function putAccountAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$group || !$account) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->addAccount($group, $account);
        return $this->redirectView($this->generateUrl('api_get_group', [ 'id' => $group->getId() ]));
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function deleteAccountAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$group || !$account) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->removeAccount($group, $account);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function putRoomAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$group || !$room) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->addRoom($group, $room);
        return $this->redirectView($this->generateUrl('api_get_group', [ 'id' => $group->getId() ]));
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function deleteRoomAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$group || !$room) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->removeRoom($group, $room);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }
}
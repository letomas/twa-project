<?php


namespace App\Controller\Rest;

use App\Entity\Account;
use App\Entity\Group;
use App\Entity\Room;
use App\Service\GroupOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class GroupController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Group")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class GroupController extends AbstractFOSRestController
{
    protected $groupOperation;

    /**
     * GroupController constructor.
     * @param $groupOperation
     */
    public function __construct(GroupOperation $groupOperation)
    {
        $this->groupOperation = $groupOperation;
    }

    public function cgetAction ()
    {
        $groups = $this->getDoctrine()->getRepository(Group::class)->findAll();
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
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        if(!$group) {
            throw $this->createNotFoundException();
        }

        return $group;
    }

    public function deleteAction($id)
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        if (!$group) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->remove($group);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }

    public function putAccountAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$group || !$account) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->addAccount($group, $account);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }

    public function deleteAccountAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$group || !$account) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->removeAccount($group, $account);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }

    public function putRoomAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$group || !$room) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->addRoom($group, $room);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }

    public function deleteRoomAction($id, $slug) {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$group || !$room) {
            throw $this->createNotFoundException();
        }

        $this->groupOperation->removeRoom($group, $room);
        return $this->redirectView($this->generateUrl('api_get_groups'));
    }
}
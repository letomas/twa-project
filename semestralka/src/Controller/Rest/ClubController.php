<?php


namespace App\Controller\Rest;

use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Room;
use App\Service\ClubOperation;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

/**
 * Class ClubController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Club")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class ClubController extends AbstractFOSRestController
{
    /**
     * @var ClubOperation
     */
    protected $clubOperation;

    /**
     * ClubController constructor.
     * @param $clubOperation
     */
    public function __construct(ClubOperation $clubOperation)
    {
        $this->clubOperation = $clubOperation;
    }

    /**
     * @return Club[]|object[]
     */
    public function cgetAction ()
    {
        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();
        if(!$clubs) {
            throw $this->createNotFoundException();
        }

        return $clubs;
    }

    /**
     *
     * @param $id
     * @return object|null
     */
    public function getAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if(!$club) {
            throw $this->createNotFoundException();
        }

        return $club;
    }

    /**
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if (!$club) {
            throw $this->createNotFoundException();
        }

        $this->clubOperation->remove($club);
        return $this->redirectView($this->generateUrl('api_get_clubs'));
    }

    /**
     * @param $id
     * @return array
     */
    public function getAccountsAction($id) {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if (!$club) {
            throw $this->createNotFoundException();
        }

        $members = $club->getMembers();
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
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        if (!$club) {
            throw $this->createNotFoundException();
        }

        $rooms = $club->getRooms();
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
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$club || !$account) {
            throw $this->createNotFoundException();
        }

        $this->clubOperation->addAccount($club, $account);
        return $this->redirectView($this->generateUrl('api_get_club', [ 'id' => $club->getId() ]));
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function deleteAccountAction($id, $slug) {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$club || !$account) {
            throw $this->createNotFoundException();
        }

        $this->clubOperation->removeAccount($club, $account);
        return $this->redirectView($this->generateUrl('api_get_clubs'));
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function putRoomAction($id, $slug) {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$club || !$room) {
            throw $this->createNotFoundException();
        }

        $this->clubOperation->addRoom($club, $room);
        return $this->redirectView($this->generateUrl('api_get_club', [ 'id' => $club->getId() ]));
    }

    /**
     * @param $id
     * @param $slug
     * @return View
     */
    public function deleteRoomAction($id, $slug) {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        if (!$club || !$room) {
            throw $this->createNotFoundException();
        }

        $this->clubOperation->removeRoom($club, $room);
        return $this->redirectView($this->generateUrl('api_get_clubs'));
    }
}
<?php


namespace App\Controller\Rest;

use App\Entity\Group;
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
    protected $groupController;

    /**
     * GroupController constructor.
     * @param $groupController
     */
    public function __construct(GroupController $groupController)
    {
        $this->groupController = $groupController;
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
}
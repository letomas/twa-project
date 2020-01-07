<?php


namespace App\Controller\Rest;

use App\Service\BuildingOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class BuildingController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Building")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class BuildingController extends AbstractFOSRestController
{
    protected $buildingOperation;

    /**
     * BuildingController constructor.
     * @param $buildingOperation
     */
    public function __construct(BuildingOperation $buildingOperation)
    {
        $this->buildingOperation = $buildingOperation;
    }

    public function cgetAction ()
    {
        $buildings = $this->getDoctrine()->getRepository(Building::class)->findAll();
        if(!$buildings) {
            throw $this->createNotFoundException();
        }

        return $buildings;
    }

    /**
     *
     * @param $id
     * @return object|null
     */
    public function getAction($id)
    {
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);
        if(!$building) {
            throw $this->createNotFoundException();
        }

        return $building;
    }
}
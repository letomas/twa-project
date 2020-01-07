<?php


namespace App\Controller\Rest;

use App\Entity\Building;
use App\Service\BuildingOperation;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BuildingController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Building")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class BuildingController extends AbstractFOSRestController
{
    /**
     * @var BuildingOperation
     */
    protected $buildingOperation;

    /**
     * BuildingController constructor.
     * @param $buildingOperation
     */
    public function __construct(BuildingOperation $buildingOperation)
    {
        $this->buildingOperation = $buildingOperation;
    }

    /**
     * @param Request $request
     * @return QueryBuilder
     */
    public function cgetAction (Request $request)
    {
        $filter = $request->query->get('filter');
        $buildings = $this->getDoctrine()->getRepository(Building::class)->findAllQueryBuilder($filter);
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

    /**
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {        
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);
        if (!$building) {
            throw $this->createNotFoundException();
        }

        $this->buildingOperation->remove($building);
        return $this->redirectView($this->generateUrl('api_get_buildings'));
    }
}
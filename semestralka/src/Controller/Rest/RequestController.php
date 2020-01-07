<?php


namespace App\Controller\Rest;

use App\Entity\Request;
use App\Service\RequestOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class RequestController
 * @package App\Controller\Rest
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Request")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class RequestController extends AbstractFOSRestController
{
    protected $requestOperation;

    /**
     * RequestController constructor.
     * @param $requestOperation
     */
    public function __construct(RequestOperation $requestOperation)
    {
        $this->requestOperation = $requestOperation;
    }

    public function cgetAction ()
    {
        $requests = $this->getDoctrine()->getRepository(Request::class)->findAll();
        if(!$requests) {
            throw $this->createNotFoundException();
        }

        return $requests;
    }

    /**
     *
     * @param $id
     * @return object|null
     */
    public function getAction($id)
    {
        $request = $this->getDoctrine()->getRepository(Request::class)->find($id);
        if(!$request) {
            throw $this->createNotFoundException();
        }

        return $request;
    }
}
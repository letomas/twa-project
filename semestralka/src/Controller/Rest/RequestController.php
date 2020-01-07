<?php


namespace App\Controller\Rest;

use App\Entity\Request;
use App\Service\RequestOperation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

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

    public function cgetAction (HttpRequest $request)
    {
        $filter = $request->query->get('filter');
        $requests = $this->getDoctrine()->getRepository(Request::class)->findAllQueryBuilder($filter);
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

    public function deleteAction($id)
    {
        $request = $this->getDoctrine()->getRepository(Request::class)->find($id);
        if (!$request) {
            throw $this->createNotFoundException();
        }

        $this->requestOperation->remove($request);
        return $this->redirectView($this->generateUrl('api_get_requests'));
    }
}
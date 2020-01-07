<?php


namespace App\Controller\Rest;

use App\Entity\Request;
use App\Form\RequestType;
use App\Service\RequestOperation;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
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
    /**
     * @var RequestOperation
     */
    protected $requestOperation;

    /**
     * RequestController constructor.
     * @param $requestOperation
     */
    public function __construct(RequestOperation $requestOperation)
    {
        $this->requestOperation = $requestOperation;
    }

    /**
     * @param HttpRequest $request
     * @return QueryBuilder
     */
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

    /**
     * @param $id
     * @param ParamFetcherInterface $fetcher
     * @return View|FormInterface
     *
     * @Rest\RequestParam(name="start")
     * @Rest\RequestParam(name="end")
     * @Rest\RequestParam(name="attendees")
     * @Rest\RequestParam(name="room")
     */
    public function putAction($id, ParamFetcherInterface $fetcher) {
        $request = $this->getDoctrine()->getRepository(Request::class)->find($id);
        if (!$request) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(
            RequestType::class, $request, [
            'csrf_protection' => false,
        ])->submit($fetcher->all());

        if ( !$form->isSubmitted() || !$form->isValid() ) {
            return $form;
        }

        $this->requestOperation->update();
        return $this->redirectView($this->generateUrl('api_get_request', [ 'id' => $request->getId() ]));
    }

    /**
     * @param $id
     * @return View
     */
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
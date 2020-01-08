<?php

namespace App\Controller;

use App\Entity\Request;
use App\Form\RequestType;
use App\Service\RequestOperation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RequestController
 * @package App\Controller
 * @Route("/requests")
 */
class RequestController extends AbstractController
{
    /**
     * @var
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
     * @Route("/", name="requests")
     */
    public function listAction()
    {
        $requests = $this->getDoctrine()->getRepository(Request::class)->findAll();

        return $this->render('request/index.html.twig', [
            'requests' => $requests,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="employee_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $request = $this->getDoctrine()->getRepository(Request::class)->find($id);

        if ($request === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('request/detail.html.twig', [
            'request' => $request,
        ]);
    }

    /**
     * @Route("/create", name="request_create", defaults={"id": null})
     * @Route("/edit/{id}", name="request_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @param HttpRequest $req
     * @return Response
     */
    public function editAction($id, HttpRequest $req)
    {
        $request = $id ?
            $this->getDoctrine()->getRepository(Request::class)->find($id) : new request();

        if(!$request) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(RequestType::class, $request);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            if($id) {
                $this->requestOperation->update();
            } else {
                $request->setCreateBy($this->getUser());
                $request->setCreateTime(new \DateTime());
                $this->requestOperation->save($request);
            }

            return $this->redirectToRoute('request_detail', [
                'id' => $request->getId(),
            ]);
        }

        if($id) {
            return $this->render('request/edit.html.twig', [
                'form' => $form->createView(),
                'request' => $request,
            ]);
        } else {
            return $this->render('request/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/remove/{id}", name="request_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $request = $this->getDoctrine()->getRepository(Request::class)->find($id);

        if ($request === null)
        {
            throw $this->createNotFoundException();
        }

        $this->requestOperation->remove($request);

        return $this->redirectToRoute('requests');
    }
}

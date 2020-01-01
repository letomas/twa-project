<?php

namespace App\Controller;

use App\Entity\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="requests")
     */
    public function listAction()
    {
        $requests = $this->getDoctrine()->getRepository(Request::class)->findAll();

        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
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
     * @return Response
     */
    public function editAction($id, $request)
    {
        $request = $id ?
            $this->getDoctrine()->getRepository(Request::class)->find($id) : new request();

        if(!$request) {
            throw $this->createNotFoundException();
        }
        /*
            $form = $this->createForm(RequestType::class, $request);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($id) {
                    $em->flush();
                } else {
                    $em->persist($request);
                    $em->flush();
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
        */

        return $this->redirectToRoute('requests');
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

        $em = $this->getDoctrine()->getManager();
        $em->remove($request);
        $em->flush();

        return $this->redirectToRoute('requests');
    }
}

<?php

namespace App\Controller;

use App\Entity\Building;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuildingController
 * @package App\Controller
 * @Route("/buildings")
 */
class BuildingController extends AbstractController
{
    /**
     * @Route("/", name="buildings")
     */
    public function listAction()
    {
        $buildings = $this->getDoctrine()->getRepository(Building::class)->findAll();

        return $this->render('building/index.html.twig', [
            'controller_name' => 'BuildingController',
        ]);
    }

    /**
     * @Route("/create", name="building_create", defaults={"id": null})
     * @Route("/edit/{id}", name="building_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function editAction($id, $request)
    {
        $building = $id ?
            $this->getDoctrine()->getRepository(Building::class)->find($id) : new Building();
        if(!$building) {
            throw $this->createNotFoundException();
        }
        /*
            $form = $this->createForm(BuildingType::class, $building);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($id) {
                    $em->flush();
                } else {
                    $em->persist($building);
                    $em->flush();
                }

                return $this->redirectToRoute('building_detail', [
                    'id' => $building->getId(),
                ]);
            }

            if($id) {
                return $this->render('building/edit.html.twig', [
                    'form' => $form->createView(),
                    'building' => $building,
                ]);
            } else {
                return $this->render('building/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        */

        return $this->redirectToRoute('buildings');
    }

    /**
     * @Route("/remove/{id}", name="building_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);

        if ($building === null)
        {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($building);
        $em->flush();

        return $this->redirectToRoute('buildings');
    }
}

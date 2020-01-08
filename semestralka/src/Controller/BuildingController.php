<?php

namespace App\Controller;

use App\Entity\Building;
use App\Form\BuildingType;
use App\Service\BuildingOperation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @var
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
     * @Route("/", name="buildings")
     */
    public function listAction()
    {
        $buildings = $this->getDoctrine()->getRepository(Building::class)->findAll();

        return $this->render('building/index.html.twig', [
            'buildings' => $buildings,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="building_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);

        if ($building === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('building/detail.html.twig', [
            'building' => $building,
        ]);
    }

    /**
     * @Route("/create", name="building_create", defaults={"id": null})
     * @Route("/edit/{id}", name="building_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editAction($id, Request $request)
    {
        $building = $id ?
            $this->getDoctrine()->getRepository(Building::class)->find($id) : new Building();

        if(!$building) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(BuildingType::class, $building)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($id) {
                $this->buildingOperation->update();
            } else {
                $this->buildingOperation->save($building);
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
    }

    /**
     * @Route("/remove/{id}", name="building_remove", requirements={"id": "\d+"})
     *
     * @IsGranted("ROLE_SUPER_ADMIN")
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

        $this->buildingOperation->remove($building);

        return $this->redirectToRoute('buildings');
    }
}

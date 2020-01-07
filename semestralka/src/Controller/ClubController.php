<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\GroupType;
use App\Service\ClubOperation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ClubController
 * @package App\Controller
 * @Route("/clubs")
 */
class ClubController extends AbstractController
{
    /**
     * @var
     */
    protected $clubOperation;

    /**
     * ClubController constructor.
     * @param $clubOperation
     */
    public function __construct(ClubOperation $clubOperation)
    {
        $this->clubOperation = $clubOperation;
    }

    /**
     * @Route("/", name="clubs")
     */
    public function listAction()
    {
        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();

        return $this->render('club/index.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="club_detail", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);

        if ($club === null)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('club/detail.html.twig', [
            'club' => $club,
        ]);
    }

    /**
     * @Route("/create", name="club_create", defaults={"id": null})
     * @Route("/edit/{id}", name="club_edit", requirements={"id": "\d+"})
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editAction($id, Request $request)
    {
        $club = $id ?
            $this->getDoctrine()->getRepository(Club::class)->find($id) : new Club();

        if(!$club) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(GroupType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($id) {
                $this->clubOperation->update();
            } else {
                $this->clubOperation->save($club);
            }

            return $this->redirectToRoute('club_detail', [
                'id' => $club->getId(),
            ]);
        }

        if($id) {
            return $this->render('club/edit.html.twig', [
                'form' => $form->createView(),
                'club' => $club,
            ]);
        } else {
            return $this->render('club/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/remove/{id}", name="club_remove", requirements={"id": "\d+"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);

        if ($club === null)
        {
            throw $this->createNotFoundException();
        }

        $this->clubOperation->remove($club);

        return $this->redirectToRoute('clubs');
    }
}

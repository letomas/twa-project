<?php

namespace App\Controller;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();

        return $this->render('index.html.twig', [
            'rooms' => $rooms,
        ]);
    }
}

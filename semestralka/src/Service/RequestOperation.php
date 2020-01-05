<?php


namespace App\Service;

use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RequestOperation
 * @package App\Service
 */
class RequestOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * RequestOperation constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     */
    public function save(Request $request)
    {
        $this->em->persist($request);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request)
    {
        $this->em->remove($request);
        $this->em->flush();
    }
}
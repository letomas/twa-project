<?php


namespace App\Service;

use App\Entity\Building;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BuildingOperation
 * @package App\Service
 */
class BuildingOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * BuildingOperation constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Building $building
     */
    public function save(Building $building)
    {
        $this->em->persist($building);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Building $building
     */
    public function remove(Building $building)
    {
        $this->em->remove($building);
        $this->em->flush();
    }
}
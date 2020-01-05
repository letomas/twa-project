<?php


namespace App\Service;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GroupOperation
 * @package App\Service
 */
class GroupOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * GroupOperation constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Group $group
     */
    public function save(Group $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Group $group
     */
    public function remove(Group $group)
    {
        $this->em->remove($group);
        $this->em->flush();
    }
}
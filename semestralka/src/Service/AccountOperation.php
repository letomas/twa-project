<?php


namespace App\Service;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountOperation
 * @package App\Service
 */
class AccountOperation
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * AccountOperation constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Account $account
     */
    public function save(Account $account)
    {
        $password = $this->passwordEncoder->encodePassword($account, $account->getPlainPassword());
        $account->eraseCredentials();
        $account->setPassword($password);

        $this->em->persist($account);
        $this->em->flush();
    }

    public function update()
    {
        $this->em->flush();
    }

    /**
     * @param Account $account
     */
    public function remove(Account $account)
    {
        $this->em->remove($account);
        $this->em->flush();
    }
}
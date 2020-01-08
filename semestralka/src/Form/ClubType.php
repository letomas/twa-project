<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ClubType extends AbstractType
{
    protected $security;
    /**
     * AccountType constructor.
     * @param $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name: '])
            ->add('manageBy', EntityType::class, [
                'class' => Account::class, 'choice_label' => 'username',
                'multiple' => true, 'required' => false, 'label' => 'Manage by: '
            ])
            ->add('subClubs', EntityType::class, [
                'class' => Club::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Subgroups: '
            ])
            ->add('superClub', EntityType::class, [
                'class' => Club::class, 'choice_label' => 'name',
                'multiple' => false, 'required' => false, 'label' => 'Supergroup: '
            ])
            ->add('rooms', EntityType::class, [
                'class' => Room::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Rooms: ',
            ])
            ->add('members', EntityType::class, [
                'class' => Account::class, 'choice_label' => 'username',
                'multiple' => true, 'required' => false, 'label' => 'Members: '
            ])
            ->add( 'submit', SubmitType::class, ['label' => 'Create'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}

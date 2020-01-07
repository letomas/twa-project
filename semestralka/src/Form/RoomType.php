<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Building;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class RoomType extends AbstractType
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
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')){
            $builder
                ->add('name', TextType::class, ['label' => 'Name: '])
                ->add('capacity', NumberType::class, ['label' => 'Capacity: '])
                ->add('description', TextType::class, ['label' => 'Description: '])
                ->add('type', ChoiceType::class, [
                    'label' => 'Room type',
                    'choices' => [
                        'public' => 'public',
                        'private' => 'private',
                    ],
                    'required' => true,
                    'multiple' => false,
                ])
                ->add('manageBy', EntityType::class, [
                    'class' => Account::class, 'choice_label' => 'username',
                    'multiple' => false, 'required' => false, 'label' => 'Manage by: '
                ])
                ->add('building', EntityType::class, [
                    'class' => Building::class, 'choice_label' => 'name',
                    'multiple' => false, 'required' => true, 'label' => 'Building: '
                ])
            ;
        }else if ($this->security->isGranted('ROLE_GROUP_ADMIN')){
            $builder
                ->add('name', TextType::class, ['label' => 'Name: '])
                ->add('type', TextType::class, ['label' => 'Type: '])
                ->add('manageBy', EntityType::class, [
                    'class' => Account::class, 'choice_label' => 'username',
                    'multiple' => false, 'required' => false, 'label' => 'Manage by: '
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}

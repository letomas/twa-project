<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Group;
use App\Entity\Request;
use App\Entity\Room;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountSuperAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Username: '])
            ->add('password', TextType::class, ['label' => 'Password: '])
            ->add('mail', TextType::class, ['label' => 'E-mail: '])
            ->add('firstName', TextType::class, ['label' => 'FirstName: '])
            ->add('lastName', TextType::class, ['label' => 'LastName: '])
            ->add('roles', ChoiceType::class, [
                'label' => 'User role',
                'choices' => [
                    'Visitor' => 'ROLE_VISITOR',
                    'User' => 'ROLE_USER',
                    'Room user' => 'ROLE_ROOM_USER',
                    'Room admin' => 'ROLE_ROOM_ADMIN',
                    'Group member' => 'ROLE_GROUP_MEMBER',
                    'Group admin' => 'ROLE_GROUP_ADMIN',
                    'Super admin' => 'ROLE_SUPER_ADMIN'
                ],
                'multiple' => true,
            ])
            ->add('requestsAttendees', EntityType::class,[
                'class' => Request::class, 'choice_label' => 'id',
                'multiple' => true, 'required' => false, 'label' => 'Requests: '
            ])
            ->add('roomOccupy', EntityType::class, [
                'class' => Room::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Room user: '
            ])
            ->add('roomsManager', EntityType::class, [
                'class' => Room::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Manage rooms: '
            ])
            ->add('groupManager', EntityType::class, [
                'class' => Group::class, 'choice_label' => 'name',
                'multiple' => false, 'require' => false, 'label' => 'Manage group: '
            ])
            ->add('groups', EntityType::class, [
                'class' => Group::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Member of groups: '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Room;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountGroupAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Username: '])
            ->add('password', TextType::class, ['label' => 'Password: '])
            ->add('mail', TextType::class, ['label' => 'E-mail: '])
            ->add('firstName', TextType::class, ['label' => 'FirstName: '])
            ->add('lastName', TextType::class, ['label' => 'LastName: '])
            ->add('roomsManager', EntityType::class, [
                'class' => Room::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Manage rooms: '
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

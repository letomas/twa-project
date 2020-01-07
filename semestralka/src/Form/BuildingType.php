<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, ['label' => 'City: '])
            ->add('country', TextType::class, ['label' => 'Country: '])
            ->add('id_address', TextType::class, ['label' => 'Id address: '])
            ->add('street', TextType::class, ['label' => 'Street: '])
            ->add('streetNumber', TextType::class, ['label' => 'Street number: '])
            ->add('name', TextType::class, ['label' => 'Name: '])
            ->add('state', TextType::class, ['label' => 'State: '])
            ->add('rooms', EntityType::class, [
                'class' => Room::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Rooms: '
            ])
            ->add( 'submit', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Building::class,
        ]);
    }
}
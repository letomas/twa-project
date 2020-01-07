<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Request;
use App\Entity\Room;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => 'Validate until:',
                'format' => DateTimeType::HTML5_FORMAT,
                'data' => new \DateTime(),
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Validate until:',
                'format' => DateTimeType::HTML5_FORMAT,
                'data' => new \DateTime(),
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('attendees', EntityType::class, [
                'class' => Account::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => true, 'label' => 'Attendees: '
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class, 'choice_label' => 'name',
                'multiple' => false, 'required' => true, 'label' => 'Room: '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}

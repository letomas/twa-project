<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Room;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                ->add('type', TextType::class, ['label' => 'Type: '])
                ->add('manageBy', EntityType::class, [
                    'class' => Account::class, 'choice_label' => 'name',
                    'multiple' => false, 'required' => false, 'label' => 'Manage by: '
                ])
                ->add('building', EntityType::class, [
                    'class' => Room::class, 'choice_label' => 'name',
                    'multiple' => false, 'required' => false, 'label' => 'Building: '
                ])
            ;
        }else if ($this->security->isGranted('ROLE_GROUP_ADMIN')){
            $builder
                ->add('name', TextType::class, ['label' => 'Name: '])
                ->add('type', TextType::class, ['label' => 'Type: '])
                ->add('manageBy', EntityType::class, [
                    'class' => Account::class, 'choice_label' => 'name',
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

<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Club;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('name', TextType::class, ['label' => 'Username: '])
            ->add('manageBy', EntityType::class, [
                'class' => Account::class, 'choice_label' => 'name',
                'multiple' => false, 'required' => false, 'label' => 'Manage by: '
            ])
            ->add('subGroup', EntityType::class, [
                'class' => Club::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Subgroups: '
            ])
            ->add('superGroup', EntityType::class, [
                'class' => Club::class, 'choice_label' => 'name',
                'multiple' => false, 'required' => false, 'label' => 'Subgroups: '
            ])
            ->add('rooms', EntityType::class, [
                'class' => Club::class, 'choice_label' => 'name',
                'multiple' => true, 'required' => false, 'label' => 'Subgroups: ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}

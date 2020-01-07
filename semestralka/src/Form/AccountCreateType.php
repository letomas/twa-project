<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Username: '])
            ->add('plainPassword', PasswordType::class, ['label' => 'Password: '])
            ->add('mail', TextType::class, ['label' => 'E-mail: '])
            ->add('firstName', TextType::class, ['label' => 'FirstName: '])
            ->add('lastName', TextType::class, ['label' => 'LastName: '])
            ->add( 'submit', SubmitType::class, ['label' => 'Create'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}

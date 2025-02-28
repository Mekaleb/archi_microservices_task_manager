<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email', 'required' => true
            ])
            ->add('fisrtname', TextType::class,  [
                'label' => 'Nom', 'required' => true
            ])
            ->add('lastname', TextType::class,  [
                'label' => 'Prenom', 'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe', 'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => ['Utilisateur' => 'ROLE_USER', 'Administrateur' => 'ROLE_ADMIN', 'Gestionaire' => 'ROLE_MANAGER'],
                'multiple' => false,
                'label' => 'Rôles', 'required' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer'
            ]);
            $builder->get('roles')->addModelTransformer(new CallbackTransformer(
                fn ($rolesArray) => $rolesArray[0] ?? null,
                fn ($rolesString) => [$rolesString]
            ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

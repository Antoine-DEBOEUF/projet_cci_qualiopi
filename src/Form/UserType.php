<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom :',
                    'required' => false,
                ]
            )

            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'Prénom(s) :',
                    'required' => false,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email :',
                    'required' => false,
                ]
            )

            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'label' => 'Mot de passe',
                    'required' => false,
                    'mapped' => false,
                    'invalid_message' => 'Les mots de passe ne correspondent pas',

                    'first_options' => ['label' => 'Mot de passe :', 'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => 4096, 'min' => 8]),
                        new Assert\Regex(pattern: '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/',)
                    ]],

                    'second_options' => ['label' => 'Confirmez le mot de passe :']
                ]
            )

            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Description (facultatif) :',
                    'required' => false,
                ]
            )



            ->add(
                'roles',
                ChoiceType::class,
                [
                    'label' => 'Role :',
                    'required' => false,
                    'placeholder' => 'Sélectionner un rôle',
                    'choices' =>
                    [
                        'Formateur' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN'
                    ],
                    'expanded' => true,
                    'multiple' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'isAdmin' => false,
            'sanitize_html' => true
        ]);
    }
}

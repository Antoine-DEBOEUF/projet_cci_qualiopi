<?php

namespace App\Form;

use App\Entity\Documents;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre :',
                    'required' => false
                ]
            )

            ->add(
                'formation',
                TextType::class,
                [
                    'label' => 'Formation :',
                    'required' => false
                ]
            )

            ->add('');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
            'sanitize_html' => true,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name', TextType::class, [
                'label_attr' => ['style' => 'margin-right: 98px;'],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du domaine ne doit pas être vide.']),
                    new Regex(['pattern' => '/^[a-zA-Z\s]+$/','message' => 'Le nom du domaine ne doit contenir que des lettres et des espaces.']),
                    new Length([
                        'min' => 10,
                        'max' => 50,
                        'minMessage' => 'Le nom du domaine doit être au moins 10 caractères.',
                        'maxMessage' => 'Le nom du domaine ne peut pas dépasser 50 caractères.',
                    ]),
                ],
            ])
            ->add('submit',SubmitType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}

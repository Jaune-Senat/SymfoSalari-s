<?php

namespace App\Form;

use App\Entity\Salarie;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class SalarieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => [
                "class" => "uk-input"
            ]])
            ->add('prenom', TextType::class,[
                'attr' => [
                "class" => "uk-input"
            ]])
            ->add('dateNaissance', DateType::class,[
                'attr' => [
                    "class" => "uk-input"
                ],
                "widget" => "single_text"
            ])
            ->add('adresse', TextType::class,[
                'attr' => [
                    "class" => "uk-input"
                ]
            ])
            ->add('cp', TextType::class,[
                'attr' => [
                    "class" => "uk-input"
                ]
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    "class" => "uk-input"
                ]
            ])
            ->add('dateEmbauche', DateType::class,[
                'attr' => [
                    "class" => "uk-input"
                ],
                "widget" => "single_text"
            ])
            ->add('entreprise', EntityType::class,[
                'class' => Entreprise::class,
                'choice_label' => 'raisonSociale',
                'attr' => [
                    "class" => "uk-select"
                ]
            ])
            ->add('Valider', SubmitType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salarie::class,
            'attr' => array('class' => 'uk-form-stacked')
        ]);
    }
}

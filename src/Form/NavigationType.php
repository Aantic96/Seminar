<?php

namespace App\Form;

use App\Entity\Navigation;
use App\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: ['attr' => [
                'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
            ],])
            ->add('page', EntityType::class, [
                'class' => Page::class,
                'choice_label' => 'title',
                'attr' => [ 'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Navigation::class,
        ]);
    }
}

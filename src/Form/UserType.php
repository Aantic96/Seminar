<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: ['attr' => [
        'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
    ],])
            ->add('email',  options: ['attr' => [
                'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
            ],])
            ->add('password', PasswordType::class, options: ['attr' => [
                'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
            ],])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'attr' => [ 'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

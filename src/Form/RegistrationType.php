<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Password', 'attr' => [
                    'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
                ],],
                'second_options' => ['label' => 'Repeat Password', 'attr' => [
                    'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
                ],],
            ]);
    }

}
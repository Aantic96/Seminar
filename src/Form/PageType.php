<?php

namespace App\Form;

use App\Entity\Page;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options: ['attr' => [
                'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
            ],])
            ->add('content', options: ['attr' => [
                'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', // Apply Tailwind classes
            ],])
            ->add('image', FileType::class, ['data_class' => null, 'attr' => [ 'class' => 'w-full py-2 px-3 border border-gray-300 rounded-md', ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Song;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\CategoryRepository;

class SongType extends AbstractType
{
    private $Repositorysong;

    public function __construct(CategoryRepository $CategoryRepository)
    {
        $this->CategoryRepository = $CategoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $categories = $this->CategoryRepository->findAll();
        $categoryChoices = [];

        foreach ($categories as $category) {
            $categoryChoices[$category->getName()] = $category;
        }

        $builder
            ->add('title')
            ->add('artist')
            ->add('url')
            ->add('category', ChoiceType::class, [
                'choices' => $categoryChoices
            ])
            ->add('image');
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}

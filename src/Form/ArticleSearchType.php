<?php

namespace App\Form;

use App\Entity\Form\ArticleSearchDto;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleSearchType
 *
 * @package App\Form
 */
class ArticleSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'author',
                EntityType::class,
                [
                    'class' => User::class,
                    'label' => 'article.form.labels.author',
                    'placeholder' => '---',
                    'required' => false
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'article.form.labels.title',
                    'placeholder' => '---',
                    'required' => false
                ]
            )
            ->add(
                'page',
                HiddenType::class
            )
            ->add('search', SubmitType::class, ['label' => 'localisation.buttons.search']);

        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleSearchDto::class,
        ]);
    }
}
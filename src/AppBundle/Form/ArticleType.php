<?php
namespace AppBundle\Form;

use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\DependencyInjection\Container;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('title', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Field are required'
                    ])
                ]
            ])
            ->add('preview')
            ->add('content')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Article',
            'csrf_protection' => false,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['title']
                ])
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article';
    }
}
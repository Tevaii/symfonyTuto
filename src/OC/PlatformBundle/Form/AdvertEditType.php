<?php

namespace OC\PlatformBundle\Form;

use OC\PlatformBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = '%';

        $builder
            ->add('date', DateTimeType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            ->add('published', CheckboxType::class, array('required' => false))
            ->add('save', SubmitType::class)
            ->add('image', ImageType::class)
            /*
       * Rappel :
       ** - 1er argument : nom du champ, ici « categories », car c'est le nom de l'attribut
       ** - 2e argument : type du champ, ici « CollectionType » qui est une liste de quelque chose
       ** - 3e argument : tableau d'options du champ
       ** - 4e argument : query builder permettant de recuperér les category commençant par $pattern
       */
            ->add('categories', EntityType::class, array(
                'class'        => 'OCPlatformBundle:Category',
                'choice_label' => 'name',
                'multiple'     => true,
                'query_builder'=>function(CategoryRepository $repository) use($pattern){
                    return $repository->getLikeQueryBuilder($pattern);
                }
            ))
            ->add('save', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_platformbundle_advert';
    }


}

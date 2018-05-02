<?php
/**
 * Created by PhpStorm.
 * User: alice simon
 * Date: 31/03/2018
 * Time: 17:15
 */

namespace AppBundle\Form;


class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('content')
            ->add('photo', FileType::class)
            ->add('price');
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Message'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_messaage';
    }
}
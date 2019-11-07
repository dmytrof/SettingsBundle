<?php

/*
 * This file is part of the DmytrofSettingsBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\SettingsBundle\Form\Type;

use Symfony\Component\Form\{AbstractType, CallbackTransformer, Extension\Core\Type\ChoiceType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntegerCheckboxType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'choices' => array_flip([
               null => 'label.integer_checkbox.reset',
               0 => 'label.integer_checkbox.no',
               1 => 'label.integer_checkbox.yes',
           ]),
       ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->resetViewTransformers()
            ->resetModelTransformers()
            ->addModelTransformer(new CallbackTransformer(function ($value) {
                if (!is_null($value)) {
                    return (int) $value;
                }
                return $value;
            }, function ($value) {
                if (!is_null($value)) {
                    return (bool) $value;
                }
                return $value;
            }))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
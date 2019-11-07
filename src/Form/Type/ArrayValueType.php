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

use Dmytrof\SettingsBundle\SettingValue\ArrayValue;
use Symfony\Component\Form\{Extension\Core\Type\CollectionType,
    Extension\Core\Type\TextType,
    AbstractType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrayValueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => TextType::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return CollectionType::class;
    }
}
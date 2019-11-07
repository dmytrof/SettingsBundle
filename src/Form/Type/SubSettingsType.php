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

use DFStudio\SettingsBundle\Settings\SubSettingsInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubSettingsType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => SubSettingsInterface::class,
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return SettingsType::class;
    }
}
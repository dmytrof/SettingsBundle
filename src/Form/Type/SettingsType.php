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

use Dmytrof\SettingsBundle\{Settings\SettingsInterface, SettingValue\PreSubmitDataModifierInterface};
use Symfony\Component\Form\{AbstractType, FormBuilderInterface, FormEvent, FormEvents};
use Symfony\Component\OptionsResolver\{Options, OptionsResolver};

class SettingsType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => SettingsInterface::class,
                'from_db' => false,
                'settings' => null,
                'api_choices' => false,
                'csrf_protection' => false,
            ])
            ->setAllowedTypes('from_db', 'bool')
            ->setAllowedTypes('settings', ['null', SettingsInterface::class])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $formEvent) use ($options, $builder) {
            $settings = null;
            if ($formEvent->getData() instanceof $options['data_class']) {
                /** @var SettingsInterface $settings */
                $settings = $formEvent->getData();
            } else if ($options['settings'] instanceof $options['data_class']) {
                /** @var SettingsInterface $settings */
                $settings = $options['settings'];
            }
            if ($settings) {
                foreach ($settings->getSettings() as $name => $value) {
                    if ($value->isEditable() || $options['from_db'] && $value->isChangeable()) {
                        $formEvent->getForm()->add(
                            $value
                                ->createFormBuilder($builder, $name, $options)
                                ->setAutoInitialize(false)
                                ->getForm()
                        );
                    }
                }
                foreach ($settings->getSubSettings() as $name => $value) {
                    $formEvent->getForm()->add(
                        $builder
                            ->create($name, SubSettingsType::class, [
                                'settings' => $options['settings'] ? $value : null,
                                'required' => false,
                                'api_choices' => $options['api_choices'],
                            ])
                            ->setAutoInitialize(false)
                            ->getForm()
                    );
                }
            }
        });
    }
}
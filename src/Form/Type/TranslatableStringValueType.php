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

use Dmytrof\LanguageBundle\Service\LanguageManager;
use Dmytrof\SettingsBundle\SettingValue\TranslatableStringValue;
use Symfony\Component\Form\{Extension\Core\Type\TextType, FormBuilderInterface, AbstractType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatableStringValueType extends AbstractType
{
    /**
     * @var LanguageManager
     */
    protected $languageManager;

    /**
     * TranslatableStringValueType constructor.
     * @param LanguageManager $languageManager
     */
    public function __construct(LanguageManager $languageManager)
    {
        $this->languageManager = $languageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translationFormType' => TextType::class,
            'data_class' => TranslatableStringValue::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->languageManager->getLanguages() as $language) {
            $builder->add($language->getLocale(), $options['translationFormType'], [
                'label' => (string) $language->getOrigTitle().($language->isDefault() ? '[Default]' : ''),
                'required' => $language->isDefault(),
            ]);
        }
    }
}
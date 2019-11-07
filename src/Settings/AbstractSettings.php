<?php

/*
 * This file is part of the DmytrofSettingsBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\SettingsBundle\Settings;

use Symfony\Contracts\Translation\TranslatorInterface;
use Dmytrof\LanguageBundle\Service\LanguageManager;
use Dmytrof\SettingsBundle\{Exception\SettingsException,
    Form\Type\SettingsType,
    Model\SettingValue,
    Settings\Traits\SettingsTrait};

abstract class AbstractSettings implements SettingsInterface
{
    use SettingsTrait;

    public const SETTING_VALUE_ENTITY_CLASS = null;
    public const FORM_CLASS = SettingsType::class;
    public const FORM_CLASS_OPTIONS = [];

    /**
     * @var LanguageManager
     */
    protected $languageManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * AbstractSettings constructor.
     * @param LanguageManager $languageManager
     * @param TranslatorInterface $translator
     */
    public function __construct(LanguageManager $languageManager, TranslatorInterface $translator)
    {
        $this->languageManager = $languageManager;
        $this->translator = $translator;
        $this->init();
    }

    /**
     * Initiates needed data
     */
    protected function init()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFormClass(): string
    {
        if (!static::FORM_CLASS) {
            throw new SettingsException(sprintf('Undefined form class (%s)', get_class($this)));
        }
        return static::FORM_CLASS;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingValueEntityClass(): string
    {
        if (!static::SETTING_VALUE_ENTITY_CLASS) {
            throw new SettingsException(sprintf('Undefined setting value entity class (%s)', get_class($this)));
        }
        if (!is_subclass_of (static::SETTING_VALUE_ENTITY_CLASS, SettingValue::class)) {
            throw new SettingsException(sprintf('Setting value entity class (%s) must be instance of %s', static::SETTING_VALUE_ENTITY_CLASS, SettingValue::class));
        }
        return static::SETTING_VALUE_ENTITY_CLASS;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormClassOptions(array $options = []): array
    {
        if (!is_array(static::FORM_CLASS_OPTIONS)) {
            throw new SettingsException(sprintf('Undefined form class options (%s)', get_class($this)));
        }
        return $options + static::FORM_CLASS_OPTIONS;
    }

    /**
     * @return LanguageManager
     */
    public function getLanguageManager(): LanguageManager
    {
        return $this->languageManager;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getSettingValueIdPrefix(): string;

    /**
     * {@inheritDoc}
     */
    abstract public function save(array $options = []): SettingsInterface;

    /**
     * {@inheritDoc}
     */
    public function translateDefault(string $message, array $parameters = [], ?string $domain = null): string
    {
        return $this->trans($this->getDefaultLocaleClosure()->call($this), $message, $parameters, $domain);
    }

    /**
     * {@inheritDoc}
     */
    public function trans(string $locale, string $message, array $parameters = [], ?string $domain = null): string
    {
        return $this->getTranslator()->trans($message, $parameters, $domain ?: 'settings', $locale);
    }

    /**
     * {@inheritDoc}
     */
    public function prepareDefaultTranslations(string $message, array $parameters = [], ?string $domain = null): array
    {
        $translations = [];
        foreach ($this->getLanguageManager()->getLocales() as $locale) {
            $translations[$locale] = $this->trans($locale, $message, $parameters, $domain);
        }
        return $translations;
    }

    /**
     * Returns current locale
     * @return \Closure
     */
    public function getCurrentLocaleClosure(): \Closure
    {
        $languageManager = $this->languageManager;
        return function() use ($languageManager) {
            return $languageManager->getCurrentLocale();
        };
    }

    /**
     * Returns default locale
     * @return \Closure
     */
    public function getDefaultLocaleClosure(): \Closure
    {
        $languageManager = $this->languageManager;
        return function() use ($languageManager) {
            return $languageManager->getDefaultLocale();
        };
    }

    public function __debugInfo()
    {
        return array_diff_key(get_object_vars($this), [
            'languageManager'    => false,
            'translator'         => false,
        ]);
    }
}
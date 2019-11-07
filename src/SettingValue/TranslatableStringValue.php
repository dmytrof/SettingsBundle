<?php

/*
 * This file is part of the DmytrofSettingsBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\SettingsBundle\SettingValue;

use Dmytrof\SettingsBundle\Form\Type\TranslatableStringValueType;
use Doctrine\Common\Inflector\Inflector;
use Dmytrof\SettingsBundle\Exception\{BadMethodCallException, InvalidArgumentException, SettingValueException};

class TranslatableStringValue extends AbstractValue implements PreSubmitDataModifierInterface
{
    protected const DEFAULT_FORM_CLASS = TranslatableStringValueType::class;

    /**
     * @var string|\Closure
     */
    protected $currentLocale;

    /**
     * @var string|\Closure
     */
    protected $defaultLocale;

    /**
     * @var array
     */
    protected $defaultTranslations = [];

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * TranslatableStringValue constructor.
     * @param $defaultLocale
     * @param $currentLocale
     * @param null $defaultValue
     */
    public function __construct($defaultLocale, $currentLocale, $defaultValue = null)
    {
        $this->setDefaultLocale($defaultLocale);
        $this->setCurrentLocale($currentLocale);
        parent::__construct($defaultValue);
    }

    /**
     * Returns current locale
     * @return string
     */
    public function getCurrentLocale(): string
    {
        if (!$this->currentLocale) {
            throw new SettingValueException('Undefined current locale');
        }
        if ($this->currentLocale instanceof \Closure) {
            $locale = $this->currentLocale->call($this);
            if (!$locale) {
                $locale = $this->getDefaultLocale();
            }
            return $locale;
        }
        return $this->currentLocale;
    }

    /**
     * Sets current locale
     * @param string|\Closure $currentLocale
     * @return TranslatableStringValue
     */
    public function setCurrentLocale($currentLocale): self
    {
        if (!is_string($currentLocale) && !$currentLocale instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Current locale must be string or %s. Input was: %s',\Closure::class, gettype($currentLocale)));
        }
        $this->currentLocale = $currentLocale;
        return $this;
    }

    /**
     * Returns default locale
     * @return string
     */
    public function getDefaultLocale(): string
    {
        if (!$this->defaultLocale) {
            throw new SettingValueException('Undefined default locale');
        }
        if ($this->defaultLocale instanceof \Closure) {
            return $this->defaultLocale->call($this);
        }
        return $this->defaultLocale;
    }

    /**
     * Sets default locale
     * @param string|\Closure $defaultLocale
     * @return TranslatableStringValue
     */
    public function setDefaultLocale($defaultLocale): self
    {
        if (!is_string($defaultLocale) && !$defaultLocale instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Default locale must be string or %s. Input was: %s',\Closure::class, gettype($defaultLocale)));
        }
        $this->defaultLocale = $defaultLocale;
        return $this;
    }

    /**
     * Returns default translations
     * @return array
     */
    public function getDefaultTranslations(): array
    {
        if ($this->defaultTranslations instanceof \Closure)
        {
            return (array) $this->defaultTranslations->call($this);
        }
        return $this->defaultTranslations;
    }

    /**
     * Sets default translations
     * @param array|\Closure $defaultTranslations
     * @return TranslatableStringValue
     */
    public function setDefaultTranslations($defaultTranslations): self
    {
        $this->defaultTranslations = $defaultTranslations;
        return $this;
    }

    /**
     * Returns default translation for locale
     * @param string|null $locale
     * @return string|null
     */
    public function getDefaultTranslation(?string $locale = null): ?string
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }
        return isset($this->getDefaultTranslations()[$locale]) ? $this->getDefaultTranslations()[$locale] : null;
    }

    /**
     * Sets default translation for locale
     * @param string|null $locale
     * @param string|null $value
     * @return TranslatableStringValue
     */
    public function setDefaultTranslation(?string $locale, ?string $value): self
    {
        if (!$locale) {
            $locale = $this->getDefaultLocale();
        }
        $this->defaultTranslations[$locale] = $value;
        return $this;
    }

    /**
     * Sets default value
     * @param \Closure|mixed $defaultValue
     * @return AbstractValue
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        parent::setDefaultValue($defaultValue);
        $this->setDefaultTranslation(null, parent::getDefaultValue());
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->getDefaultTranslation();
    }

    /**
     * Returns translations
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Sets translations
     * @param array $translations
     * @return TranslatableStringValue
     */
    public function setTranslations(array $translations): self
    {
        $this->translations = $translations;
        return $this;
    }

    /**
     * Returns translation for locale
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslation(?string $locale = null): ?string
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }
        return $this->hasTranslation($locale) && !is_null($this->getTranslations()[$locale])
            ? $this->getTranslations()[$locale]
            : $this->getDefaultTranslation($locale)
        ;
    }

    /**
     * Sets translation for locale
     * @param string|null $locale
     * @param $value
     * @return TranslatableStringValue
     */
    public function setTranslation(?string $locale, $value): self
    {
        if (!$locale) {
            $locale = $this->getDefaultLocale();
        }
        $this->translations[$locale] = $value !== $this->getDefaultTranslation($locale) ? $value : null;
        return $this;
    }

    /**
     * Returns default translations
     * @return string|null
     */
    public function getDefaultLocaleTranslation(): ?string
    {
        return $this->getTranslation($this->getDefaultLocale());
    }

    /**
     * Removes translation for locale
     * @param string|null $locale
     * @return TranslatableStringValue
     */
    public function removeTranslation(?string $locale = null): self
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }
        $this->setTranslations(array_diff_key($this->getTranslations(), [$locale => false]));
        return $this;
    }

    /**
     * Checks if translation exists for locale
     * @param string|null $locale
     * @return bool
     */
    public function hasTranslation(?string $locale = null): bool
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }
        return array_key_exists($locale, $this->getTranslations());
    }

    /**
     * Returns translations to edit
     * @return array
     */
    public function getTranslationsToEdit(): array
    {
        $locales = array_merge(array_keys($this->getDefaultTranslations()), array_keys($this->getTranslations()));
        $translationsToEdit = [];
        foreach ($locales as $locale) {
            $translationsToEdit[$locale] = $this->getTranslation($locale);
        }
        return $translationsToEdit;
    }

    public function __get(string $locale): ?string
    {
        return $this->getTranslation($locale);
    }

    public function __set(string $locale, $value): self
    {
        return $this->setTranslation($locale, $value);
    }

    public function __isset(string $locale)
    {
        return $this->hasTranslation($locale);
    }

    public function __unset(string $locale)
    {
        return $this->removeTranslation($locale);
    }

    public function __call(string $method, array $args = [])
    {
        if (substr($method, 0, 3) == 'get') {
            $locale = Inflector::camelize(substr($method, 3));
            return $this->getTranslation($locale);
        } elseif (substr($method, 0, 3) == 'set') {
            $locale = Inflector::camelize(substr($method, 3));
            return $this->setTranslation($locale, $args[0]);
        }
        throw new BadMethodCallException(sprintf('Undefined method "%s"', $method));
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        return $this->setTranslations($value);
    }

    /**
     * Returns value for current locale
     * @param string|null $locale
     * @return string|null
     */
    public function getValue(?string $locale = null): ?string
    {
        return $this->getTranslation($locale);
    }

    /**
     * Returns real value of translations
     * @return array
     */
    public function getRealValue(): array
    {
        return $this->getTranslations();
    }

    /**
     * Returns real value of translations
     * @return array
     */
    public function getEditableValue(): array
    {
        return $this->getTranslationsToEdit();
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableName(string $name): string
    {
        return $name.'Translations';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultSetting(SettingValueInterface $settingsValue): SettingValueInterface
    {
        if (!$settingsValue instanceof self) {
            throw new SettingValueException(sprintf('Default setting for %s must be %s', self::class, self::class));
        }
        $this->setDefaultTranslations(function() use($settingsValue) {
            return $settingsValue->getTranslationsToEdit();
        });
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onPreSubmitDataModify(array $data, string $name): array
    {
        if (isset($data[$name])) {
            $data[$this->getEditableName($name)] = $data[$name];
            unset($data[$name]);
        }
        return $data;
    }

    public function __debugInfo()
    {
        return array_diff_key(get_object_vars($this), [
            'currentLocale'     => false,
            'defaultLocale'     => false,
        ]);
    }
}
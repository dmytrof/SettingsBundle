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

use Dmytrof\SettingsBundle\Exception\SettingValueException;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractValue implements SettingValueInterface
{
    protected const DEFAULT_FORM_CLASS = null;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * @var array
     */
    protected $formClassOptions = [];

    protected $defaultValue;
    protected $value;

    /**
     * @var bool
     */
    protected $editable = true;

    /**
     * @var bool
     */
    protected $changeable = true;

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * AbstractValue constructor.
     * @param null $defaultValue
     */
    public function __construct($defaultValue = null)
    {
        $this->setDefaultValue($defaultValue);
    }

    /**
     * Returns default value
     * @return mixed
     */
    public function getDefaultValue()
    {
        if ($this->defaultValue instanceof \Closure) {
            return $this->defaultValue->call($this);
        }
        return $this->defaultValue;
    }

    /**
     * Sets default value
     * @param \Closure|mixed $defaultValue
     * @return AbstractValue
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return is_null($this->value) ? $this->getDefaultValue() : $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        $this->value = $value !== $this->getDefaultValue() ? $value : null;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRealValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableValue()
    {
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableName(string $name): string
    {
        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setEditable(bool $editable = true): SettingValueInterface
    {
        $this->editable = $editable;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEditable(): bool
    {
        return $this->editable;
    }

    /**
     * {@inheritdoc}
     */
    public function isChangeable(): bool
    {
        return $this->changeable;
    }

    /**
     * {@inheritdoc}
     */
    public function setChangeable(bool $changeable = true): SettingValueInterface
    {
        $this->editable = $this->changeable = $changeable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * Sets visible (in settings)
     * @param bool $visible
     * @return AbstractValue
     */
    public function setVisible(bool $visible = true): SettingValueInterface
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function createFormBuilder(FormBuilderInterface $builder, string $name, array $options = []): FormBuilderInterface
    {
        $name = $this->getEditableName($name);
        $formClass = $this->getFormClass();
        $formClassOptions = $this->getFormClassOptions() + [
                'required' => false,
        ];
        $formBuilder = $builder->create($name, $formClass, $formClassOptions);

        if ($formBuilder->hasOption('api_choices')) {
            $formBuilder = $builder->create($name, $formClass, [
                'api_choices' => isset($options['api_choices']) ? $options['api_choices'] : false,
            ] + $formClassOptions);
        }
        return $formBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormClass(): string
    {
        if (is_null($this->formClass)) {
            if (!static::DEFAULT_FORM_CLASS) {
                throw new SettingValueException(sprintf('Undefined form class in %s', get_class($this)));
            }
            return static::DEFAULT_FORM_CLASS;
        }
        return $this->formClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setFormClass(string $formClass): SettingValueInterface
    {
        $this->formClass = $formClass;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormClassOptions(): array
    {
        return $this->formClassOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function setFormClassOptions(array $formClassOptions): SettingValueInterface
    {
        $this->formClassOptions = $formClassOptions;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultSetting(SettingValueInterface $settingsValue): SettingValueInterface
    {
        if (!$settingsValue instanceof self) {
            throw new SettingValueException(sprintf('Default setting for %s must be %s', self::class, self::class));
        }
        $this->setDefaultValue(function() use($settingsValue) {
            return $settingsValue->getValue();
        });
        return $this;
    }
}
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

use Dmytrof\SettingsBundle\{Form\Type\ArrayValueType, Exception\InvalidArgumentException};

class ArrayValue extends AbstractValue
{
    protected const DEFAULT_FORM_CLASS = ArrayValueType::class;

    /**
     * Returns default value
     * @return array|null
     */
    public function getDefaultValue(): ?array
    {
        $defaultValue = parent::getDefaultValue();
        return is_null($defaultValue) ? null : (array) $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        if (!is_null($defaultValue) && !is_array($defaultValue) && !$defaultValue instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Default value must be an array, null or %s. Input was: %s',\Closure::class, gettype($defaultValue)));
        }
        return parent::setDefaultValue($defaultValue);
    }

    /**
     * Returns value
     * @return array|null
     */
    public function getValue(): ?array
    {
        return parent::getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        if (!is_null($value) && !is_array($value)) {
            throw new InvalidArgumentException(sprintf('Value must be an array or null. Input was: %s',gettype($value)));
        }
        return parent::setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRealValue()
    {
        $value = parent::getRealValue();
        return function () use ($value) {
            return $value;
        };
    }
}
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

use Dmytrof\SettingsBundle\Exception\InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FloatValue extends AbstractValue
{
    protected const DEFAULT_FORM_CLASS = TextType::class;

    /**
     * Returns default value
     * @return float|null
     */
    public function getDefaultValue(): ?float
    {
        $defaultValue = parent::getDefaultValue();
        return is_null($defaultValue) ? null : (float) $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        if (is_scalar($defaultValue)) {
            $defaultValue = (float) $defaultValue;
        }
        if (!is_null($defaultValue) && !is_float($defaultValue) && !$defaultValue instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Default value must be float, null or %s. Input was: %s',\Closure::class, gettype($defaultValue)));
        }
        return parent::setDefaultValue($defaultValue);
    }

    /**
     * Returns value
     * @return float|null
     */
    public function getValue(): ?float
    {
        return parent::getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        if (is_scalar($value)) {
            $value = (float) $value;
        }
        if (!is_null($value) && !is_float($value)) {
            throw new InvalidArgumentException(sprintf('Value must be float or null. Input was: %s',gettype($value)));
        }
        return parent::setValue($value);
    }
}
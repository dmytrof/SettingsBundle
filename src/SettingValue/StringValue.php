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

class StringValue extends AbstractValue
{
    protected const DEFAULT_FORM_CLASS = TextType::class;

    /**
     * Returns default value
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        $defaultValue = parent::getDefaultValue();
        return is_null($defaultValue) ? null : (string) $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        if (is_scalar($defaultValue)) {
            $defaultValue = (string) $defaultValue;
        }
        if (!is_null($defaultValue) && !is_string($defaultValue) && !$defaultValue instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Default value must be string, null or %s. Input was: %s',\Closure::class, gettype($defaultValue)));
        }
        return parent::setDefaultValue($defaultValue);
    }

    /**
     * Returns value
     * @return string|null
     */
    public function getValue(): ?string
    {
        return parent::getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        if (is_scalar($value)) {
            $value = (string) $value;
        }
        if (!is_null($value) && !is_string($value)) {
            throw new InvalidArgumentException(sprintf('Value must be string or null. Input was: %s',gettype($value)));
        }
        return parent::setValue($value);
    }
}
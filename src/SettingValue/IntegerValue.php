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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class IntegerValue extends AbstractValue
{
    protected const DEFAULT_FORM_CLASS = IntegerType::class;

    /**
     * Returns default value
     * @return int|null
     */
    public function getDefaultValue(): ?int
    {
        $defaultValue = parent::getDefaultValue();
        return is_null($defaultValue) ? null : (int) $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        if (is_scalar($defaultValue)) {
            $defaultValue = (int) $defaultValue;
        }
        if (!is_null($defaultValue) && !is_int($defaultValue) && !$defaultValue instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Default value must be integer, null or %s. Input was: %s',\Closure::class, gettype($defaultValue)));
        }
        return parent::setDefaultValue($defaultValue);
    }

    /**
     * Returns value
     * @return int|null
     */
    public function getValue(): ?int
    {
        return parent::getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        if (is_scalar($value)) {
            $value = (int) $value;
        }
        if (!is_null($value) && !is_int($value)) {
            throw new InvalidArgumentException(sprintf('Value must be integer or null. Input was: %s',gettype($value)));
        }
        return parent::setValue($value);
    }
}
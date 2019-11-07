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

use Dmytrof\SettingsBundle\{Form\Type\IntegerCheckboxType, Exception\InvalidArgumentException};

class BooleanValue extends AbstractValue implements PreSubmitDataModifierInterface
{
    protected const DEFAULT_FORM_CLASS = IntegerCheckboxType::class;

    /**
     * Returns default value
     * @return bool|null
     */
    public function getDefaultValue(): ?bool
    {
        $defaultValue = parent::getDefaultValue();
        return is_null($defaultValue) ? null : (bool) $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue($defaultValue): SettingValueInterface
    {
        if (is_scalar($defaultValue)) {
            $defaultValue = (bool) $defaultValue;
        }
        if (!is_null($defaultValue) && !is_bool($defaultValue) && !$defaultValue instanceof \Closure) {
            throw new InvalidArgumentException(sprintf('Default value must be boolean, null or %s. Input was: %s',\Closure::class, gettype($defaultValue)));
        }
        return parent::setDefaultValue($defaultValue);
    }

    /**
     * Returns value
     * @return bool|null
     */
    public function getValue(): ?bool
    {
        return parent::getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): SettingValueInterface
    {
        if (is_scalar($value)) {
            $value = (bool) $value;
        }
        if (!is_null($value) && !is_bool($value)) {
            throw new InvalidArgumentException(sprintf('Value must be bool or null. Input was: %s',gettype($value)));
        }
        return parent::setValue($value);
    }

    /**
     * {@inheritDoc}
     */
    public function onPreSubmitDataModify(array $data, string $name): array
    {
        if (isset($data[$name])) {
            $data[$name] = (int) $data[$name];
        }
        return $data;
    }
}
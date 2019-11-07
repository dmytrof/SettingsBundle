<?php

/*
 * This file is part of the DmytrofSettingsBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\SettingsBundle\Settings\Traits;

use Dmytrof\SettingsBundle\{Exception\SettingsException, Settings\SubSettingsInterface, SettingValue\SettingValueInterface};

trait SettingsTrait
{
    /**
     * @var array|SettingValueInterface[]
     */
    protected $settingValues;

    /**
     * @var array|SubSettingsInterface[]
     */
    protected $subSettings;

    /**
     * @return array|SettingValueInterface[]
     */
    public function getSettings(): array
    {
        if (is_null($this->settingValues)) {
            $this->settingValues = [];
            foreach (get_object_vars($this) as $name => $value) {
                if ($value instanceof SettingValueInterface) {
                    $this->settingValues[$name] = $value;
                }
            }
        }
        return $this->settingValues;
    }

    /**
     * @return array|SubSettingsInterface[]
     */
    public function getSubSettings(): array
    {
        if (is_null($this->subSettings)) {
            $this->subSettings = [];
            foreach (get_object_vars($this) as $name => $value) {
                if ($value instanceof SubSettingsInterface) {
                    $this->subSettings[$name] = $value;
                }
            }
        }
        return $this->subSettings;
    }

    /**
     * {@inheritDoc}
     */
    public function getSettingsValues(): array
    {
        $settingsValues = [];
        foreach ($this->getSettings() as $name => $value) {
            if ($value->isVisible()) {
                foreach (['get', 'is', 'has'] as $prefix) {
                    $methodName = $prefix.ucfirst($name);
                    if (method_exists($this, $methodName)) {
                        $settingsValues[$name] = $this->$methodName();
                    }
                }
            }
        }
        foreach ($this->getSubSettings() as $name => $value) {
            $settingsValues[$name] = $value->getSettingsValues();
        }
        return $settingsValues;
    }

    /**
     * {@inheritDoc}
     */
    public function getRealValues(): array
    {
        $realValues = [];
        foreach ($this->getSettings() as $name => $value) {
            if ($value->isChangeable()) {
                $realValues[$name] = $value->getRealValue();
            }
        }
        foreach ($this->getSubSettings() as $name => $value) {
            $realValues[$name] = $value->getRealValues();
        }
        return $realValues;
    }

    /**
     * {@inheritDoc}
     */
    public function getEditableValues(): array
    {
        $editableValues = [];
        foreach ($this->getSettings() as $name => $value) {
            if ($value->isEditable()) {
                $editableValues[$value->getEditableName($name)] = $value->getEditableValue();
            }
        }
        foreach ($this->getSubSettings() as $name => $value) {
            $editableValues[$name] = $value->getEditableValues();
        }
        return $editableValues;
    }

    public function __get(string $property)
    {
        foreach ($this->getSettings() as $name => $value) {
            if ($property === $value->getEditableName($name)) {
                return $value;
            } else if ($property === $name) {
                return $value;
            }
        }
        throw new SettingsException(sprintf('Undefined property %s of class %s', $property, get_class($this)));
    }
}
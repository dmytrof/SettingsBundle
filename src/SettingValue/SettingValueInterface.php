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

use Symfony\Component\Form\FormBuilderInterface;

interface SettingValueInterface
{
    /**
     * Returns real value in property (without default value handler)
     * @return mixed
     */
    public function getRealValue();

    /**
     * Returns editable value in property (with default value handler)
     * @return mixed
     */
    public function getEditableValue();

    /**
     * Returns editable name of property
     * @param string $name
     * @return string
     */
    public function getEditableName(string $name): string;

    /**
     * Returns value
     * @return mixed
     */
    public function getValue();

    /**
     * Sets value
     * @param $value
     * @return SettingValueInterface
     */
    public function setValue($value): self;

    /**
     * Returns default value
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * Sets default value
     * @param mixed $defaultValue
     * @return SettingValueInterface
     */
    public function setDefaultValue($defaultValue): self;

    /**
     * Creates form builder
     * @param FormBuilderInterface $builder
     * @param string $name
     * @param array $options
     * @return FormBuilderInterface
     */
    public function createFormBuilder(FormBuilderInterface $builder, string $name, array $options = []): FormBuilderInterface;

    /**
     * Returns form class for setting value
     * @return string
     */
    public function getFormClass(): string;

    /**
     * Sets form class for setting value
     * @param string $formClass
     * @return SettingValueInterface
     */
    public function setFormClass(string $formClass): self;

    /**
     * Returns form class options
     * @return array
     */
    public function getFormClassOptions(): array;

    /**
     * Sets form class options
     * @param array $formClassOptions
     * @return SettingValueInterface
     */
    public function setFormClassOptions(array $formClassOptions): self;

    /**
     * Sets default setting value interface
     * @param SettingValueInterface $settingsValue
     * @return SettingValueInterface
     */
    public function setDefaultSetting(SettingValueInterface $settingsValue): self;

    /**
     * Sets editable
     * @param bool $editable
     * @return SettingValueInterface
     */
    public function setEditable(bool $editable = true): self;

    /**
     * Checks if editable
     * @return bool
     */
    public function isEditable(): bool;

    /**
     * Sets changeable (not editable by user, but changeable by system)
     * @param bool $changeable
     * @return SettingValueInterface
     */
    public function setChangeable(bool $changeable = true): self;

    /**
     * Checks if changeable
     * @return bool
     */
    public function isChangeable(): bool;

    /**
     * Checks if visible in settings
     * @return bool
     */
    public function isVisible(): bool;

    /**
     * Sets visible (in settings)
     * @param bool $visible
     * @return SettingValueInterface
     */
    public function setVisible(bool $visible = true): self;
}
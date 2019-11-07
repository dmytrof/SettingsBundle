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

use Dmytrof\SettingsBundle\SettingValue\SettingValueInterface;

interface SettingsInterface
{
    /**
     * Returns class of setting value entity
     * @return string
     */
    public function getSettingValueEntityClass(): string;

    /**
     * Returns setting value id prefix
     * @return string
     */
    public function getSettingValueIdPrefix(): string;

    /**
     * Returns form class
     * @return string
     */
    public function getFormClass(): string;

    /**
     * Returns form class options
     * @param array $options
     * @return array
     */
    public function getFormClassOptions(array $options = []): array;

    /**
     * Returns array or real values
     * @return array
     */
    public function getRealValues(): array;

    /**
     * Returns array or editable values
     * @return array
     */
    public function getEditableValues(): array;

    /**
     * Returns array or settings values
     * @return array
     */
    public function getSettingsValues(): array;

    /**
     * Returns array of setting values objects
     * @return array|SettingValueInterface[]
     */
    public function getSettings(): array;

    /**
     * Returns array of sub settings objects
     * @return array|SubSettingsInterface[]
     */
    public function getSubSettings(): array;

    /**
     * Saves settings
     * @param array $options
     * @return SettingsInterface
     */
    public function save(array $options = []): self;

    /**
     * Returns current locale
     * @return \Closure
     */
    public function getCurrentLocaleClosure(): \Closure;

    /**
     * Returns default locale
     * @return \Closure
     */
    public function getDefaultLocaleClosure(): \Closure;

    /**
     * Translates default message
     * @param string $message
     * @param array $parameters
     * @param string|null $domain
     * @return string
     */
    public function translateDefault(string $message, array $parameters = [], ?string $domain = null): string;

    /**
     * Translates message to locale
     * @param string $locale
     * @param string $message
     * @param array $parameters
     * @param string|null $domain
     * @return string
     */
    public function trans(string $locale, string $message, array $parameters = [], ?string $domain = null): string;

    /**
     * Returns array of default translations
     * @param string $message
     * @param array $parameters
     * @param string|null $domain
     * @return array
     */
    public function prepareDefaultTranslations(string $message, array $parameters = [], ?string $domain = null): array;
}
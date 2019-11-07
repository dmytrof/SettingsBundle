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

interface PreSubmitDataModifierInterface
{
    /**
     * Modifies data on pre submit
     * @param array $data
     * @param string $name
     * @return array
     */
    public function onPreSubmitDataModify(array $data, string $name): array;
}
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

use Dmytrof\SettingsBundle\Settings\SettingsInterface;

interface SubSettingsInterface extends SettingsInterface
{
    /**
     * Returns parent settings
     * @return SettingsInterface
     */
    public function getParent(): SettingsInterface;

    /**
     * Sets parent
     * @param SettingsInterface $parent
     * @return SubSettingsInterface
     */
    public function setParent(SettingsInterface $parent): SubSettingsInterface;
}
<?php

/*
 * This file is part of the DmytrofSettingsBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\SettingsBundle\Model;

use Dmytrof\SettingsBundle\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;

class SettingValuesCollection extends ArrayCollection
{
    /**
     * SettingValuesCollection constructor.
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct([]);
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->add($value);
    }

    /**
     * {@inheritDoc}
     */
    public function add($element)
    {
        if (!$element instanceof SettingValue) {
            throw new InvalidArgumentException(sprintf('Element must be %s. Got: %s', SettingValue::class, gettype($element)));
        }
        parent::set($element->getId(), $element);

        return true;
    }
}
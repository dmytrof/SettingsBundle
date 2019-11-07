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

class SettingValue
{
    /**
     * @var string
     */
    protected $id;

    protected $value;

    /**
     * SettingValue constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->setId($id);
    }

    /**
     * Returns ID
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Sets ID
     * @param string $id
     * @return SettingValue
     */
    protected function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns value
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets value
     * @param $value
     * @return SettingValue
     */
    public function setValue($value): self
    {
        if ($value instanceof \Closure) {
            $value = $value->call($this);
        }
        $this->value = $value;
        return $this;
    }
}
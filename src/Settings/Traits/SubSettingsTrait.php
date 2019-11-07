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

use Dmytrof\SettingsBundle\{Exception\SettingsException, Settings\SettingsInterface, Settings\SubSettingsInterface};

trait SubSettingsTrait
{
    use SettingsTrait;

    /**
     * @var SettingsInterface
     */
    protected $parent;

    /**
     * Returns parent settings
     * @return SettingsInterface
     */
    public function getParent(): SettingsInterface
    {
        if (!$this->parent instanceof SettingsInterface) {
            throw new SettingsException(sprintf('Undefined parent settings for %s', get_class($this)));
        }
        return $this->parent;
    }

    /**
     * Sets parent
     * @param SettingsInterface $parent
     * @return SubSettingsInterface
     */
    public function setParent(SettingsInterface $parent): SubSettingsInterface
    {
        $this->parent = $parent;
        $this->init();
        return $this;
    }

    /**
     * Initiates sub settings
     */
    protected function init()
    {

    }

    /**
     * Returns current locale
     * @return \Closure
     */
    public function getCurrentLocaleClosure(): \Closure
    {
        return $this->getParent()->getCurrentLocaleClosure();
    }

    /**
     * Returns default locale
     * @return \Closure
     */
    public function getDefaultLocaleClosure(): \Closure
    {
        return $this->getParent()->getDefaultLocaleClosure();
    }

    /**
     * @param string $message
     * @param array $parameters
     * @param string|null $domain
     * @return string
     */
    public function translateDefault(string $message, array $parameters = [], string $domain = null): string
    {
        return $this->getParent()->translateDefault($message, $parameters, $domain);
    }

    /**
     * @param string $locale
     * @param string $message
     * @param array $parameters
     * @param string|null $domain
     * @return string
     */
    public function trans(string $locale, string $message, array $parameters = [], ?string $domain = null): string
    {
        return $this->getParent()->trans($locale, $message, $parameters, $domain);
    }

    /**
     * @param string $message
     * @param array $parameters
     * @param string|null $domain
     * @return array
     */
    public function prepareDefaultTranslations(string $message, array $parameters = [], ?string $domain = null): array
    {
        return $this->getParent()->prepareDefaultTranslations($message, $parameters, $domain);
    }

    /**
     * {@inheritDoc}
     */
    public function save(array $options = []): SettingsInterface
    {
        throw new SettingsException(sprintf('Unable to use sub settings %s as standalone settings', get_class($this)));
    }

    /**
     * {@inheritDoc}
     */
    public function getSettingValueIdPrefix(): string
    {
        throw new SettingsException(sprintf('Unable to use sub settings %s as standalone settings', get_class($this)));
    }

    /**
     * @inheritDoc
     */
    public function getSettingValueEntityClass(): string
    {
        throw new SettingsException(sprintf('Unable to use sub settings %s as standalone settings', get_class($this)));
    }

    /**
     * @inheritDoc
     */
    public function getFormClass(): string
    {
        throw new SettingsException(sprintf('Unable to use sub settings %s as standalone settings', get_class($this)));
    }

    /**
     * @inheritDoc
     */
    public function getFormClassOptions(array $options = []): array
    {
        throw new SettingsException(sprintf('Unable to use sub settings %s as standalone settings', get_class($this)));
    }

    public function __debugInfo()
    {
        return array_diff_key(get_object_vars($this), [
            'parent'            => false,
        ]);
    }
}
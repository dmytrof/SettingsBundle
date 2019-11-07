<?php

/*
 * This file is part of the DmytrofSettingsBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\SettingsBundle\Service;

use Dmytrof\SettingsBundle\Model\{SettingValue, SettingValuesCollection};
use Dmytrof\SettingsBundle\Settings\SettingsInterface;
use Dmytrof\SettingsBundle\SettingValue\PreSubmitDataModifierInterface;
use Symfony\Component\Form\FormFactoryInterface;

class SettingsFlattener
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * PlatformSettingsFlattener constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * Flatten settings
     * @param SettingsInterface $settings
     * @return SettingValuesCollection
     */
    public function flatten(SettingsInterface $settings): SettingValuesCollection
    {
        $flattenArray = $this->convertSettingsToFlattenArray($settings);

        $collection = new SettingValuesCollection();
        $settingValueClass = $settings->getSettingValueEntityClass();
        foreach ($flattenArray as $key => $value) {
            $collection->add((new $settingValueClass($key))->setValue($value));
        }
        return $collection;
    }

    /**
     * Expands settings
     * @param SettingValuesCollection $valuesCollection
     * @param SettingsInterface $settings
     * @return SettingsInterface
     */
    public function expand(SettingValuesCollection $valuesCollection, SettingsInterface $settings): SettingsInterface
    {
        $form = $this->getFormFactory()->create($settings->getFormClass(), $settings, $settings->getFormClassOptions() + [
            'allow_extra_fields' => true,
            'from_db' => true,
            'data_class' => get_class($settings),
        ]);
        $array = $this->convertSettingsValuesCollectionToArray($valuesCollection, $settings->getSettingValueIdPrefix());
        $form->submit($this->prepareDbData($settings, $array), false);
        return $settings;
    }

    /**
     * Prepares data from DB to use in form
     * @param SettingsInterface $settings
     * @param array $data
     * @return array
     */
    protected function prepareDbData(SettingsInterface $settings, array $data): array
    {
        foreach ($settings->getSettings() as $name => $setting) {
            if ($setting instanceof PreSubmitDataModifierInterface && $setting->isEditable()) {
                $data = $setting->onPreSubmitDataModify($data, $name);
            }
        }
        foreach ($settings->getSubSettings() as $name => $subsettings) {
            $data[$name] = $this->prepareDbData($subsettings, isset($data[$name]) ? (array) $data[$name] : []);
        }
        return $data;
    }

    /**
     * Converts settings to flatten array
     * @param SettingsInterface $settings
     * @return array
     */
    protected function convertSettingsToFlattenArray(SettingsInterface $settings): array
    {
        return $this->flattenSettingsArray($settings->getRealValues(), $settings->getSettingValueIdPrefix().'.');
    }

    /**
     * Flattens the array
     * @param array $array
     * @param string $prefix
     * @return array
     */
    protected function flattenSettingsArray(array $array, string $prefix): array
    {
        $flattenArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $flattenArray = array_merge($flattenArray, $this->flattenSettingsArray($value, $prefix.$key.'.'));
            } else {
                $flattenArray[$prefix.$key] = $value;
            }
        }
        return $flattenArray;
    }

    /**
     * Converts setting values collection to array
     * @param SettingValuesCollection $valuesCollection
     * @param string $prefix
     * @return array
     */
    protected function convertSettingsValuesCollectionToArray(SettingValuesCollection $valuesCollection, string $prefix): array
    {
        $flattenArray = [];
        $prefix = rtrim($prefix, '.').'.';
        $prefixLen = strlen($prefix);
        foreach ($valuesCollection as $value) {
            if (substr($value->getId(), 0, $prefixLen) === $prefix) {
                $flattenArray[$value->getId()] = $value->getValue();
            }
        }

        return $this->expandSettingsArray($flattenArray, $prefix);
    }

    /**
     * Flattens the array
     * @param array $flattenArray
     * @param string $prefix
     * @return array
     */
    protected function expandSettingsArray(array $flattenArray, string $prefix): array
    {
        $array = [];
        foreach ($flattenArray as $key => $value) {
            $path = explode('.', str_replace($prefix, '', $key));
            $arr = &$array;
            $pathSize = sizeof($path);
            foreach ($path as $i => $part) {
                if (!isset($arr[$part]) && $i+1 < $pathSize) {
                    $arr[$part] = [];
                }
                if ($i+1 < $pathSize) {
                    $arr = &$arr[$part];
                } else {
                    if (!is_array($arr)) {
                        $arr = [];
                    }
                    $arr[$part] = $value;
                }
            }
        }
        return $array;
    }
}
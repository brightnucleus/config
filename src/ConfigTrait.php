<?php
/**
 * Config Trait
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use BrightNucleus\Config\ConfigInterface;

trait ConfigTrait
{

    /**
     * Reference to the Config object.
     *
     * @since 0.1.2
     *
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Process the passed-in configuration file.
     *
     * @since 0.1.2
     *
     * @param ConfigInterface $config The Config to process.
     */
    protected function processConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Check whether the Config has a specific key.
     *
     * To get a value several levels deep, add the keys for each level as a comma-separated list.
     *
     * @since 0.1.2
     * @since 0.1.5 Accepts list of keys.
     *
     * @param string ... List of keys.
     * @return bool Whether the key is known.
     */
    protected function hasConfigKey()
    {
        $keys = func_get_args();

        return call_user_func_array([$this->config, 'hasKey'], $keys);
    }

    /**
     * Get the Config value for a specific key.
     *
     * To get a value several levels deep, add the keys for each level as a comma-separated list.
     *
     * @since 0.1.2
     * @since 0.1.5 Accepts list of keys.
     *
     * @param string ... List of keys.
     * @return mixed Value of the key.
     */
    protected function getConfigKey()
    {
        $keys = func_get_args();

        return call_user_func_array([$this->config, 'getKey'], $keys);
    }

    /**
     * Get a (multi-dimensional) array of all the configuration settings.
     *
     * @since 0.1.4
     *
     * @return array All the configuration settings.
     */
    protected function getConfigArray()
    {
        return $this->config->getAll();
    }

    /**
     * Get an array of all the keys that are known by the Config.
     *
     * @since 0.1.2
     *
     * @return array Array of strings containing all the keys.
     */
    protected function getConfigKeys()
    {
        return $this->config->getKeys();
    }
}

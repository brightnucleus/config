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
     * @since 0.1.2
     *
     * @param string $key The key to check.
     * @return bool Whether the key is known.
     */
    protected function hasConfigKey($key)
    {
        return $this->config->hasKey($key);
    }

    /**
     * Get the Config value for a specific key.
     *
     * @since 0.1.2
     *
     * @param string $key The key for which to get the value.
     * @return mixed Value of the key.
     */
    protected function getConfigKey($key)
    {
        return $this->config->getKey($key);
    }

    /**
     * Get an array o all the keys that are known by the Config.
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

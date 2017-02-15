<?php
/**
 * Bright Nucleus Config Component.
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016-2017 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use Exception;

/**
 * Basic config processing that can be included within classes.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
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
     * @param                 string  ... List of keys.
     *
     * @throws FailedToProcessConfigException If the arguments could not be parsed into a Config.
     */
    protected function processConfig(ConfigInterface $config)
    {
        if (func_num_args() > 1) {
            try {
                $keys = func_get_args();
                array_shift($keys);
                $config = $config->getSubConfig($keys);
            } catch (Exception $exception) {
                throw new FailedToProcessConfigException(
                    sprintf(
                        _('Could not process the config with the arguments "%1$s".'),
                        print_r(func_get_args(), true)
                    )
                );
            }
        }
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
     * @param string|array $_ List of keys.
     *
     * @return bool Whether the key is known.
     */
    protected function hasConfigKey($_)
    {
        $keys = func_get_args();

        return $this->config->hasKey($keys);
    }

    /**
     * Get the Config value for a specific key.
     *
     * To get a value several levels deep, add the keys for each level as a comma-separated list.
     *
     * @since 0.1.2
     * @since 0.1.5 Accepts list of keys.
     *
     * @param string|array $_ List of keys.
     *
     * @return mixed Value of the key.
     */
    protected function getConfigKey($_)
    {
        $keys = func_get_args();

        return $this->config->getKey($keys);
    }

    /**
     * Get the callable Config value for a specific key.
     *
     * If the fetched value is indeed a callable, it will be executed with the provided arguments, and the resultant
     * value will be returned instead.
     *
     * @since 0.4.8
     *
     * @param string|array $key  Key or array of nested keys.
     * @param array        $args Optional. Array of arguments to pass to the callable.
     *
     * @return mixed Resultant value of the key's callable.
     */
    protected function getConfigCallable($key, array $args = [])
    {
        $value = $this->config->getKey($key);

        if (is_callable($value)) {
            $value = $value(...$args);
        }

        return $value;
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

    /**
     * Get a default configuration in case none was injected into the constructor.
     *
     * The name and path of the configuration needs to be set as a const called DEFAULT_CONFIG within the class
     * containing the trait. The path needs to be relative to the location of the containing class file.
     *
     * @since 0.4.2
     *
     * @return ConfigInterface Configuration settings to use.
     */
    protected function fetchDefaultConfig()
    {
        $configFile = method_exists($this, 'getDefaultConfigFile')
            ? $this->getDefaultConfigFile()
            : __DIR__ . '/../config/defaults.php';

        return $this->fetchConfig($configFile);
    }

    /**
     * Get a configuration from a specified $file.
     *
     * If file is not accessible or readable, returns an empty Config.
     *
     * @since 0.4.2
     *
     * @return ConfigInterface Configuration settings to use.
     */
    protected function fetchConfig($configFile)
    {
        if (is_string($configFile) && ! is_readable($configFile)) {
            $configFile = [];
        }

        return ConfigFactory::create($configFile);
    }
}

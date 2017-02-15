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

use Exception;

/**
 * Create new object instances that implement ConfigInterface.
 *
 * @since   0.3.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ConfigFactory
{

    /**
     * Cached contents of the config files.
     *
     * @since 0.4.3
     *
     * @var array
     */
    protected static $configFilesCache = [];

    /**
     * Create a new ConfigInterface object from a file.
     *
     * If a comma-separated list of files is provided, they are checked in sequence until the first one could be loaded
     * successfully.
     *
     * @since 0.3.0
     *
     * @param string|array $_ List of files.
     *
     * @return ConfigInterface Instance of a ConfigInterface implementation.
     */
    public static function createFromFile($_)
    {
        $files = array_reverse(func_get_args());

        if (is_array($files[0])) {
            $files = $files[0];
        }

        while (count($files) > 0) {
            try {
                $file = array_pop($files);

                if (! is_readable($file)) {
                    continue;
                }

                $config = static::createFromArray(
                    static::getFromCache($file, function ($file) {
                        return Loader::load($file);
                    })
                );

                if (null === $config) {
                    continue;
                }

                return $config;
            } catch (Exception $exception) {
                // Fail silently and try next file.
            }
        }

        return static::createFromArray([]);
    }

    /**
     * Create a new ConfigInterface object from an array.
     *
     * @since 0.3.0
     *
     * @param array $array Array with configuration values.
     *
     * @return ConfigInterface Instance of a ConfigInterface implementation.
     */
    public static function createFromArray(array $array)
    {
        try {
            return new Config($array);
        } catch (Exception $exception) {
            // Fail silently and try next file.
        }

        return null;
    }

    /**
     * Create a new ConfigInterface object.
     *
     * Tries to deduce the correct creation method by inspecting the provided arguments.
     *
     * @since 0.3.0
     *
     * @param mixed $_ Array with configuration values.
     *
     * @return ConfigInterface Instance of a ConfigInterface implementation.
     */
    public static function create($_)
    {
        if (func_num_args() < 1) {
            return static::createFromArray([]);
        }

        $arguments = func_get_args();

        if (is_array($arguments[0]) && func_num_args() === 1) {
            return static::createFromArray($arguments[0]);
        }

        return static::createFromFile($arguments);
    }

    /**
     * Create a new ConfigInterface object, by merging several files together.
     *
     * Duplicate keys in later files will override those in earlier files.
     *
     * @since 0.4.6
     *
     * @param mixed $_ Array with configuration values.
     *
     * @return ConfigInterface Instance of a ConfigInterface implementation.
     */
    public static function merge($_)
    {
        if (func_num_args() < 1) {
            return static::createFromArray([]);
        }

        $arguments = func_get_args();

        if (is_array($arguments[0]) && func_num_args() === 1) {
            return static::createFromArray($arguments[0]);
        }

        return static::mergeFromFiles($arguments);
    }

    /**
     * Create a new ConfigInterface object by merging data from several files.
     *
     * If a comma-separated list of files is provided, they are loaded in sequence and later files override settings in
     * earlier files.
     *
     * @since 0.4.6
     *
     * @param string|array $_ List of files.
     *
     * @return ConfigInterface Instance of a ConfigInterface implementation.
     */
    public static function mergeFromFiles($_)
    {
        $files = array_reverse(func_get_args());
        $data  = [];

        if (is_array($files[0])) {
            $files = array_reverse($files[0]);
        }

        while (count($files) > 0) {
            try {
                $file = array_pop($files);

                if (! is_readable($file)) {
                    continue;
                }

                $new_data = static::getFromCache($file, function ($file) {
                    return Loader::load($file);
                });

                if (null === $data) {
                    continue;
                }

                $data = array_replace_recursive($data, $new_data);
            } catch (Exception $exception) {
                // Fail silently and try next file.
            }
        }

        return static::createFromArray($data);
    }

    /**
     * Create a new ConfigInterface object from a file and return a sub-portion of it.
     *
     * The first argument needs to be the file name to load, and the subsequent arguments will be passed on to
     * `Config::getSubConfig()`.
     *
     * @since 0.4.5
     *
     * @param mixed $_ File name of the config to load as a string, followed by an array of keys to pass to
     *                 `Config::getSubConfig()`.
     *
     * @return ConfigInterface Instance of a ConfigInterface implementation.
     */
    public static function createSubConfig($_)
    {
        if (func_num_args() < 2) {
            return static::createFromArray([]);
        }

        $arguments = func_get_args();
        $file      = array_shift($arguments);

        $config = static::createFromFile($file);

        return $config->getSubConfig($arguments);
    }

    /**
     * Get a config file from the config file cache.
     *
     * @since 0.4.4
     *
     * @param string $identifier Identifier to look for in the cache.
     * @param mixed  $fallback   Fallback to use to fill the cache. If $fallback is a callable, it will be executed
     *                           with $identifier as an argument.
     *
     * @return mixed The latest content of the cache for the given identifier.
     */
    protected static function getFromCache($identifier, $fallback)
    {
        if (! array_key_exists($identifier, static::$configFilesCache)) {
            static::$configFilesCache[$identifier] = is_callable($fallback)
                ? $fallback($identifier)
                : $fallback;
        }

        return static::$configFilesCache[$identifier];
    }
}

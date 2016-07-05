<?php
/**
 * Bright Nucleus Config Component.
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
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

                if (! array_key_exists($file, static::$configFilesCache)) {
                    static::$configFilesCache[$file] = Loader::load($file);
                }

                $config = static::createFromArray(
                    static::$configFilesCache[$file]
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
}

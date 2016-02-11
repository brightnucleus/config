<?php
/**
 * Abstract Config Object
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use Exception;
use ArrayObject;
use OutOfRangeException;
use BadMethodCallException;
use BrightNucleus\Config\ConfigInterface;

/**
 * Config loader used to load config PHP files as objects.
 *
 * @since      0.1.0
 *
 * @package    BrightNucleus\Config
 * @author     Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractConfig extends ArrayObject implements ConfigInterface
{

    /**
     * Array of strings that are used as delimiters to parse configuration keys.
     *
     * @since 0.1.6
     *
     * @var array
     */
    protected $delimiter = ['\\', '/', '.'];

    /**
     * Instantiate the AbstractConfig object.
     *
     * @since 0.1.0
     * @since 0.1.6 Accepts a delimiter to parse configuration keys.
     *
     * @param array                $config    Array with settings.
     * @param string[]|string|null $delimiter A string or array of strings that are used as delimiters to parse
     *                                        configuration keys. Defaults to "\", "/" & ".".
     */
    public function __construct(array $config, $delimiter = null)
    {
        // Make sure the config entries can be accessed as properties.
        parent::__construct($config, ArrayObject::ARRAY_AS_PROPS);

        if (null !== $delimiter) {
            $this->delimiter = (array)$delimiter;
        }
    }

    /**
     * Check whether the Config has a specific key.
     *
     * To check a value several levels deep, add the keys for each level as a comma-separated list.
     *
     * @since 0.1.0
     * @since 0.1.4 Accepts list of keys.
     *
     * @param string ... List of keys.
     * @return bool
     */
    public function hasKey()
    {
        try {
            $keys = array_reverse($this->getKeyArguments(func_get_args()));

            $array = $this->getArrayCopy();
            while (count($keys) > 0) {
                $key = array_pop($keys);
                if ( ! array_key_exists($key, $array)) {
                    return false;
                }
                $array = $array[$key];
            }
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * Get the value of a specific key.
     *
     * To get a value several levels deep, add the keys for each level as a comma-separated list.
     *
     * @since 0.1.0
     * @since 0.1.4 Accepts list of keys.
     *
     * @param string ... List of keys.
     * @return mixed
     * @throws BadMethodCallException If no argument was provided.
     * @throws OutOfRangeException If an unknown key is requested.
     */
    public function getKey()
    {
        $keys = $this->getKeyArguments(func_get_args());

        if ( ! $this->hasKey($keys)) {
            throw new OutOfRangeException(sprintf(_('The configuration key %1$s does not exist.'),
                implode('->', $keys)));
        }

        $keys  = array_reverse($keys);
        $array = $this->getArrayCopy();
        while (count($keys) > 0) {
            $key   = array_pop($keys);
            $array = $array[$key];
        }

        return $array;
    }

    /**
     * Get a (multi-dimensional) array of all the configuration settings.
     *
     * @since 0.1.4
     *
     * @return array
     */
    public function getAll()
    {
        return $this->getArrayCopy();
    }

    /**
     * Get the an array with all the keys
     *
     * @since 0.1.0
     * @return array
     */
    public function getKeys()
    {
        return array_keys((array)$this);
    }

    /**
     * Extract the configuration key arguments from an arbitrary array.
     *
     * @since 0.1.6
     *
     * @param array $arguments Array as fetched through get_func_args().
     * @return array Array of strings.
     * @throws BadMethodCallException If no argument was provided.
     */
    protected function getKeyArguments($arguments)
    {
        if (count($arguments) < 1) {
            throw new BadMethodCallException(_('No configuration key was provided.'));
        }

        $keys = [];
        foreach ($arguments as $argument) {
            if (is_array($argument)) {
                $keys = array_merge($keys, $this->getKeyArguments($argument));
            }
            if (is_string($argument)) {
                $keys = array_merge($keys, $this->parseKeysString($argument));
            }
        }

        return $keys;
    }

    /**
     * Extract individual keys from a delimited string.
     *
     * @since 0.1.6
     *
     * @param string $keyString Delimited string of keys.
     * @return array Array of key strings.
     */
    protected function parseKeysString($keyString)
    {
        // Replace all of the configured delimiters by the first one, so that we can then use explode().
        $normalizedString = str_replace($this->delimiter, $this->delimiter[0], $keyString);

        return (array)explode($this->delimiter[0], $normalizedString);
    }

    /**
     * Validate the Config file.
     *
     * @since  0.1.0
     * @return boolean
     */
    abstract public function isValid();
}

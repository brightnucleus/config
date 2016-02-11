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
     * Instantiate the AbstractConfig object.
     *
     * @since 0.1.0
     *
     * @param  array $config Array with settings.
     */
    public function __construct(array $config)
    {
        // Make sure the config entries can be accessed as properties.
        parent::__construct($config, ArrayObject::ARRAY_AS_PROPS);
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
        $keys = array_reverse(func_get_args());

        $array = $this->getArrayCopy();
        while (count($keys) > 0) {
            $key = array_pop($keys);
            if ( ! array_key_exists($key, $array)) {
                return false;
            }
            $array = $array[$key];
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
        if (func_num_args() < 1) {
            throw new BadMethodCallException(_('No configuration was provided to getKey().'));
        }

        $keys = func_get_args();

        if ( ! call_user_func_array([$this, 'hasKey'], $keys)) {
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
     * Validate the Config file.
     *
     * @since  0.1.0
     * @return boolean
     */
    abstract public function isValid();
}

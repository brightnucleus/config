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
     * @since 0.1.0
     *
     * @param string $key The key to check the existence for.
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists($key, (array)$this);
    }

    /**
     * Get the value of a specific key.
     *
     * @since 0.1.0
     *
     * @param string $key The key to get the value for.
     * @return mixed
     * @throws OutOfRangeException If an unknown key is requested.
     */
    public function getKey($key)
    {
        if ( ! $this->hasKey($key)) {
            throw new OutOfRangeException(sprintf(_('The configuration key %1$s does not exist.'),
                (string)$key));
        }

        return $this[$key];
    }

    /**
     * Get the an array with all the keys
     *
     * @since 0.1.0
     * @return mixed
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

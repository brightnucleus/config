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

/**
 * Handles basic validation of the config schema.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractConfigSchema implements ConfigSchemaInterface
{

    /**
     * The defined values that are recognized.
     *
     * @var ConfigInterface
     */
    protected $defined;

    /**
     * The default values that can be overwritten.
     *
     * @var ConfigInterface
     */
    protected $defaults;

    /**
     * The required values that need to be set.
     *
     * @var ConfigInterface
     */
    protected $required;

    /**
     * Get the set of defined options.
     *
     * @since 0.1.0
     *
     * @return array|null
     */
    public function getDefinedOptions()
    {
        if (! $this->defined) {
            return null;
        }

        if ($this->defined instanceof ConfigInterface) {
            return $this->defined->getArrayCopy();
        }

        return (array)$this->defined;
    }

    /**
     * Get the set of default options.
     *
     * @since 0.1.0
     *
     * @return array|null
     */
    public function getDefaultOptions()
    {
        if (! $this->defaults) {
            return null;
        }

        if ($this->defaults instanceof ConfigInterface) {
            return $this->defaults->getArrayCopy();
        }

        return (array)$this->defaults;
    }

    /**
     * Get the set of required options.
     *
     * @since 0.1.0
     *
     * @return array|null
     */
    public function getRequiredOptions()
    {
        if (! $this->required) {
            return null;
        }

        if ($this->required instanceof ConfigInterface) {
            return $this->required->getArrayCopy();
        }

        return (array)$this->required;
    }
}

<?php
/**
 * Config Schema Interface.
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

/**
 * Interface ConfigSchemaInterface
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface ConfigSchemaInterface
{

    /**
     * Get the set of defined options.
     *
     * @since 0.1.0
     *
     * @return array|null
     */
    public function getDefinedOptions();

    /**
     * Get the set of default options.
     *
     * @since 0.1.0
     *
     * @return array|null
     */
    public function getDefaultOptions();

    /**
     * Get the set of required options.
     *
     * @since 0.1.0
     *
     * @return array|null
     */
    public function getRequiredOptions();
}

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
 * Contract to deal with configuration value validation.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface ConfigValidatorInterface
{

    /**
     * Check whether the passed-in Config is valid.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface $config
     *
     * @return bool
     */
    public function isValid(ConfigInterface $config);
}

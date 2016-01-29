<?php
/**
 * Config Validator Interface
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use BrightNucleus\Config\ConfigInterface;

/**
 * Interface ConfigValidatorInterface
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

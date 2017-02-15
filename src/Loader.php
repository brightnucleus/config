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

use BrightNucleus\Config\Exception\FailedToLoadConfigException;
use BrightNucleus\Config\Loader\LoaderFactory;

/**
 * Class Loader.
 *
 * @since   0.4.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class Loader
{

    /**
     * Static convenience function to load a configuration from an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource to load.
     *
     * @return array|null Parsed data loaded from the resource.
     * @throws FailedToLoadConfigException If the configuration could not be loaded.
     */
    public static function load($uri)
    {
        $loader = LoaderFactory::createFromUri($uri);

        return $loader->load($uri);
    }
}

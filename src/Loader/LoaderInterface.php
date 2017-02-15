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

namespace BrightNucleus\Config\Loader;

/**
 * Interface LoaderInterface.
 *
 * @since   0.4.0
 *
 * @package BrightNucleus\Config\Loader
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface LoaderInterface
{

    /**
     * Check whether the loader is able to load a given URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI to check.
     *
     * @return bool Whether the loader can load the given URI.
     */
    public static function canLoad($uri);

    /**
     * Load the configuration from an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource to load.
     *
     * @return array Data contained within the resource.
     */
    public function load($uri);
}

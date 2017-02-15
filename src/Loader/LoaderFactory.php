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

use BrightNucleus\Config\Exception\FailedToLoadConfigException;
use Exception;

/**
 * Class LoaderFactory.
 *
 * @since   0.4.0
 *
 * @package BrightNucleus\Config\Loader
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class LoaderFactory
{

    /**
     * Array of fully qualified class names of known loaders.
     *
     * @var array<string>
     *
     * @since 0.4.0
     */
    protected static $loaders = [
        'BrightNucleus\Config\Loader\PHPLoader',
    ];

    /**
     * Array of instantiated loaders.
     *
     * These are lazily instantiated and added as needed.
     *
     * @var LoaderInterface[]
     *
     * @since 0.4.0
     */
    protected static $loaderInstances = [];

    /**
     * Create a new Loader from an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource to create a loader for.
     *
     * @return LoaderInterface Loader that is able to load the given URI.
     * @throws FailedToLoadConfigException If no suitable loader was found.
     */
    public static function createFromUri($uri)
    {
        foreach (static::$loaders as $loader) {
            if ($loader::canLoad($uri)) {
                return static::getLoader($loader);
            }
        }

        throw new FailedToLoadConfigException(
            sprintf(
                _('Could not find a suitable loader for URI "%1$s".'),
                $uri
            )
        );
    }

    /**
     * Get an instance of a specific loader.
     *
     * The loader is lazily instantiated if needed.
     *
     * @since 0.4.0
     *
     * @param string $loaderClass Fully qualified class name of the loader to get.
     *
     * @return LoaderInterface Instance of the requested loader.
     * @throws FailedToLoadConfigException If the loader class could not be instantiated.
     */
    public static function getLoader($loaderClass)
    {
        try {
            if (! array_key_exists($loaderClass, static::$loaderInstances)) {
                static::$loaderInstances[$loaderClass] = new $loaderClass;
            }

            return static::$loaderInstances[$loaderClass];
        } catch (Exception $exception) {
            throw new FailedToLoadConfigException(
                sprintf(
                    _('Could not instantiate the requested loader class "%1$s".'),
                    $loaderClass
                )
            );
        }
    }

    /**
     * Register a new loader.
     *
     * @since 0.4.0
     *
     * @param string $loader Fully qualified class name of a loader implementing LoaderInterface.
     */
    public static function registerLoader($loader)
    {
        if (in_array($loader, static::$loaders, true)) {
            return;
        }

        static::$loaders [] = $loader;
    }
}

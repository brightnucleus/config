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
 * Class PHPLoader.
 *
 * @since   0.4.0
 *
 * @package BrightNucleus\Config\Loader
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class PHPLoader extends AbstractLoader
{

    /**
     * Load the configuration from an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource to load.
     *
     * @return array Data contained within the resource.
     */
    public static function canLoad($uri)
    {
        $path = pathinfo($uri);

        return 'php' === mb_strtolower($path['extension']);
    }

    /**
     * Load the contents of an resource identified by an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource.
     *
     * @return array|null Raw data loaded from the resource. Null if no data found.
     * @throws FailedToLoadConfigException If the resource could not be loaded.
     */
    protected function loadUri($uri)
    {
        try {
            // Try to load the file through PHP's include().
            // Make sure we don't accidentally create output.
            ob_start();
            $data = include($uri);
            ob_end_clean();

            return $data;
        } catch (Exception $exception) {
            throw new FailedToLoadConfigException(
                sprintf(
                    _('Could not include PHP config file "%1$s". Reason: "%2$s".'),
                    $uri,
                    $exception->getMessage()
                ),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Validate and return the URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource to load.
     *
     * @return string Validated URI.
     * @throws FailedToLoadConfigException If the URI does not exist or is not readable.
     */
    protected function validateUri($uri)
    {
        if (! is_readable($uri)) {
            throw new FailedToLoadConfigException(
                sprintf(
                    _('The requested PHP config file "%1$s" does not exist or is not readable.'),
                    $uri
                )
            );
        }

        return $uri;
    }
}

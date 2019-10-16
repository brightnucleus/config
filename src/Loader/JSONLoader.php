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
 * Class JSONLoader.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config\Loader
 * @author  Pascal Knecht <pascal.knecht99@gmail.com>
 */
class JSONLoader extends AbstractLoader
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
    public static function canLoad($uri)
    {
        $path = pathinfo($uri);

        return 'json' === mb_strtolower($path['extension']);
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
            ob_start();
            $data = json_decode(file_get_contents($uri), true);
            ob_end_clean();

            return (array)$data;
        } catch (Exception $exception) {
            throw new FailedToLoadConfigException(
                sprintf(
                    _('Could not include JSON config file "%1$s". Reason: "%2$s".'),
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
                    _('The requested JSON config file "%1$s" does not exist or is not readable.'),
                    $uri
                )
            );
        }

        return $uri;
    }
}

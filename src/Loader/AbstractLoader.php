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
 * Class AbstractLoader.
 *
 * @since   0.4.0
 *
 * @package BrightNucleus\Config\Loader
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractLoader implements LoaderInterface
{

    /**
     * Load the configuration from an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource to load.
     *
     * @return array|null Data contained within the resource. Null if no data could be loaded/parsed.
     * @throws FailedToLoadConfigException If the configuration could not be loaded.
     */
    public function load($uri)
    {
        try {
            $uri  = $this->validateUri($uri);
            $data = $this->loadUri($uri);

            return $this->parseData($data);
        } catch (Exception $exception) {
            throw new FailedToLoadConfigException(
                sprintf(
                    _('Could not load resource located at "%1$s". Reason: "%2$s".'),
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
     */
    protected function validateUri($uri)
    {
        return $uri;
    }

    /**
     * Parse the raw data and return it in parsed form.
     *
     * @since 0.4.0
     *
     * @param array|null $data Raw data to be parsed.
     *
     * @return array|null Data in parsed form. Null if no parsable data found.
     */
    protected function parseData($data)
    {
        return $data;
    }

    /**
     * Load the contents of an resource identified by an URI.
     *
     * @since 0.4.0
     *
     * @param string $uri URI of the resource.
     *
     * @return array|null Raw data loaded from the resource. Null if no data found.
     */
    abstract protected function loadUri($uri);
}

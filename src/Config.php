<?php
/**
 * Generic Config contract implementation
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use Exception;
use RuntimeException;
use InvalidArgumentException;
use UnexpectedValueException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BrightNucleus\Config\ConfigSchemaInterface as Schema;
use BrightNucleus\Config\ConfigValidatorInterface as Validator;

/**
 * Class Config
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class Config extends AbstractConfig
{

    /**
     * The schema of the Config file.
     *
     * @var Schema
     */
    protected $schema;

    /**
     * The Validator class that gets asked to do the validation of the config.
     *
     * @since 0.1.0
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Instantiate the Config object.
     *
     * It accepts either an array with the configuration settings, or a
     * filename pointing to a PHP file it can include.
     *
     * @since 0.1.0
     *
     * @param  array|string  $config    Array with settings or filename for the
     *                                  settings file.
     * @param Schema|null    $schema    Optional. Config that contains default
     *                                  values that can get overwritten.
     * @param Validator|null $validator Optional. Validator class that does the
     *                                  actual validation.
     *
     * @throws InvalidArgumentException If the config source is not a string or
     *                                  array.
     * @throws RuntimeException         If loading of the config source failed.
     * @throws UnexpectedValueException If the config file is not valid.
     */
    public function __construct(
        $config,
        Schema $schema = null,
        Validator $validator = null
    ) {
        $this->schema    = $schema;
        $this->validator = $validator;

        // Make sure $config is either a string or array.
        if ( ! (is_string($config) || is_array($config))) {
            throw new InvalidArgumentException(sprintf(
                _('Invalid configuration source: %1$s'),
                print_r($config, true)
            ));
        }

        if (is_string($config)) {
            $config = $this->fetchArrayData($config);
        }

        $config = $this->resolveOptions($config);

        parent::__construct($config);

        // Finally, validate the resulting config.
        if ( ! $this->isValid()) {
            throw new UnexpectedValueException(sprintf(
                _('ConfigInterface file is not valid: %1$s'),
                print_r($config, true)
            ));
        }
    }

    /**
     * Fetch array data from a string pointing to a file.
     *
     * @since 0.1.0
     *
     * @param  string $config           Filename for the settings file.
     * @return array                    Array with configuration settings.
     * @throws RuntimeException         If the config source is a non-existing
     *                                  file.
     * @throws RuntimeException         If loading of the config source failed.
     */
    protected function fetchArrayData($config)
    {
        if (is_string($config) && ! ('' === $config)) {

            // $config is a valid string, make sure it is an existing file.
            if ( ! file_exists($config)) {
                throw new RuntimeException(sprintf(
                    _('Non-existing configuration source: %1$s'),
                    (string)$config
                ));
            }

            // Try to load the file through PHP's include().
            $configString = $config;
            try {
                // Make sure we don't accidentally create output.
                ob_get_contents();
                $config = include($configString);
                ob_clean();

                // The included should return an array.
                if ( ! is_array($config)) {
                    throw new RuntimeException(_('File inclusion did not return an array.'));
                }
            } catch (Exception $exception) {
                throw new RuntimeException(sprintf(
                    _('Loading from configuration source %1$s failed. Reason: %2$s'),
                    (string)$configString,
                    (string)$exception->getMessage()
                ));
            }
        }

        return (array)$config;
    }

    /**
     * Process the passed-in defaults and merge them with the new values, while
     * checking that all required options are set.
     *
     * @since 0.1.0
     *
     * @param array $config             Configuration settings to resolve.
     * @return array                    Resolved configuration settings.
     * @throws UnexpectedValueException If there are errors while resolving the
     *                                  options.
     */
    protected function resolveOptions($config)
    {
        try {
            $resolver = new OptionsResolver();
            if ($this->configureOptions($resolver)) {
                $config = $resolver->resolve($config);
            }
        } catch (Exception $exception) {
            throw new UnexpectedValueException(sprintf(
                _('Error while resolving config options: %1$s'),
                $exception->getMessage()
            ));
        }

        return $config;
    }

    /**
     * Configure the possible and required options for the Config.
     *
     * This should return a bool to let the resolve_options() know whether the
     * actual resolving needs to be done or not.
     *
     * @since 0.1.0
     *
     * @param OptionsResolver $resolver Reference to the OptionsResolver
     *                                  instance.
     * @return bool Whether to do the resolving.
     * @throws UnexpectedValueException If there are errors while processing.
     */
    protected function configureOptions(OptionsResolver $resolver)
    {

        if ( ! $this->schema) {
            return false;
        }

        $defined  = $this->schema->getDefinedOptions();
        $defaults = $this->schema->getDefaultOptions();
        $required = $this->schema->getRequiredOptions();

        if ( ! $defined && ! $defaults && ! $required) {
            return false;
        }

        try {
            if ($defined) {
                $resolver->setDefined($defined);
            }
            if ($defaults) {
                $resolver->setDefaults($defaults);
            }
            if ($required) {
                $resolver->setRequired($required);
            }
        } catch (Exception $exception) {
            throw new UnexpectedValueException(sprintf(
                _('Error while processing config options: %1$s'),
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * Validate the Config file.
     *
     * @since  0.1.0
     *
     * @return boolean
     */
    public function isValid()
    {
        if ($this->validator) {
            return $this->validator->isValid($this);
        }

        return true;
    }
}

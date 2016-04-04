<?php
/**
 * Bright Nucleus Config Component.
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use Assert;
use BrightNucleus\Config\ConfigSchemaInterface as Schema;
use BrightNucleus\Config\ConfigValidatorInterface as Validator;
use BrightNucleus\Exception\InvalidArgumentException;
use BrightNucleus\Exception\RuntimeException;
use BrightNucleus\Exception\UnexpectedValueException;
use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Generic implementation of a Config object.
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
     * @since 0.1.6 Accepts a delimiter to parse configuration keys.
     *
     * @param array|string         $config    Array with settings or filename for the
     *                                        settings file.
     * @param Schema|null          $schema    Optional. Config that contains default
     *                                        values that can get overwritten.
     * @param Validator|null       $validator Optional. Validator class that does the
     *                                        actual validation.
     * @param string[]|string|null $delimiter A string or array of strings that are used as delimiters to parse
     *                                        configuration keys. Defaults to "\", "/" & ".".
     *
     * @throws InvalidArgumentException If the config source is not a string or
     *                                        array.
     * @throws RuntimeException         If loading of the config source failed.
     * @throws UnexpectedValueException If the config file is not valid.
     */
    public function __construct(
        $config,
        Schema $schema = null,
        Validator $validator = null,
        $delimiter = null
    ) {
        $this->schema    = $schema;
        $this->validator = $validator;

        // Make sure $config is either a string or array.
        if (! (is_string($config) || is_array($config))) {
            throw new InvalidArgumentException(
                sprintf(
                    _('Invalid configuration source: %1$s'),
                    print_r($config, true)
                )
            );
        }

        if (is_string($config)) {
            $config = $this->fetchArrayData($config);
        }

        // Run the $config through the OptionsResolver.
        Assert\that($config)->isArray();
        $config = $this->resolveOptions($config);

        // Instantiate the parent class.
        try {
            parent::__construct($config, $delimiter);
        } catch (Exception $exception) {
            throw new RuntimeException(
                sprintf(
                    _('Could not instantiate the configuration through its parent. Reason: %1$s'),
                    $exception->getMessage()
                )
            );
        }

        // Finally, validate the resulting config.
        if (! $this->isValid()) {
            throw new UnexpectedValueException(
                sprintf(
                    _('ConfigInterface file is not valid: %1$s'),
                    print_r($config, true)
                )
            );
        }
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

    /**
     * Fetch array data from a string pointing to a file.
     *
     * @since 0.1.0
     *
     * @param  string $filename Filename for the settings file.
     *
     * @return array                    Array with configuration settings.
     * @throws RuntimeException         If the config source is a non-existing
     *                                  file.
     * @throws RuntimeException         If loading of the config source failed.
     */
    protected function fetchArrayData($filename)
    {
        try {
            // Assert that $filename is a readable file.
            Assert\that($filename)
                ->notEmpty()
                ->file()
                ->readable();

            // Try to load the file through PHP's include().
            // Make sure we don't accidentally create output.
            ob_get_contents();
            $config = include($filename);
            ob_clean();

            // The included should return an array.
            Assert\that($config)->isArray();
        } catch (Exception $exception) {
            throw new RuntimeException(
                sprintf(
                    _('Loading from configuration source %1$s failed. Reason: %2$s'),
                    (string)$filename,
                    (string)$exception->getMessage()
                )
            );
        }

        return $config;
    }

    /**
     * Process the passed-in defaults and merge them with the new values, while
     * checking that all required options are set.
     *
     * @since 0.1.0
     *
     * @param array $config Configuration settings to resolve.
     *
     * @return array                    Resolved configuration settings.
     * @throws UnexpectedValueException If there are errors while resolving the
     *                                  options.
     */
    protected function resolveOptions($config)
    {
        if (! $this->schema) {
            return $config;
        }

        try {
            $resolver = new OptionsResolver();
            if ($this->configureOptions($resolver)) {
                $config = $resolver->resolve($config);
            }
        } catch (Exception $exception) {
            throw new UnexpectedValueException(
                sprintf(
                    _('Error while resolving config options: %1$s'),
                    $exception->getMessage()
                )
            );
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
     *
     * @return bool Whether to do the resolving.
     * @throws UnexpectedValueException If there are errors while processing.
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $defined  = $this->schema->getDefinedOptions();
        $defaults = $this->schema->getDefaultOptions();
        $required = $this->schema->getRequiredOptions();

        if (! $defined && ! $defaults && ! $required) {
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
            throw new UnexpectedValueException(
                sprintf(
                    _('Error while processing config options: %1$s'),
                    $exception->getMessage()
                )
            );
        }

        return true;
    }
}

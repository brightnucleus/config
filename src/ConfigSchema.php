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

use BrightNucleus\Exception\InvalidArgumentException;

/**
 * Generic implementation of a configuration requirements check.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ConfigSchema extends AbstractConfigSchema
{

    /**
     * The key that is used in the schema to define a default value.
     */
    const DEFAULT_VALUE = 'default';
    /**
     * The key that is used in the schema to define a required value.
     */
    const REQUIRED_KEY = 'required';

    /**
     * Instantiate a ConfigSchema object.
     *
     * @since 0.1.0
     *
     * @param ConfigInterface|array $schema The schema to parse.
     *
     * @throws InvalidArgumentException
     */
    public function __construct($schema)
    {
        if ($schema instanceof ConfigInterface) {
            $schema = $schema->getArrayCopy();
        }

        if (! is_array($schema)) {
            throw new InvalidArgumentException(
                sprintf(
                    _('Invalid schema source: %1$s'),
                    print_r($schema, true)
                )
            );
        }

        array_walk($schema, [$this, 'parseSchema']);
    }

    /**
     * Parse a single provided schema entry.
     *
     * @since 0.1.0
     *
     * @param mixed  $data The data associated with the key.
     * @param string $key  The key of the schema data.
     */
    protected function parseSchema($data, $key)
    {
        $this->parseDefined($key);

        if (array_key_exists(self::REQUIRED_KEY, $data)) {
            $this->parseRequired(
                $key,
                $data[self::REQUIRED_KEY]
            );
        }

        if (array_key_exists(self::DEFAULT_VALUE, $data)) {
            $this->parseDefault(
                $key,
                $data[self::DEFAULT_VALUE]
            );
        }
    }

    /**
     * Parse the set of defined values.
     *
     * @since 0.1.0
     *
     * @param string $key The key of the schema data.
     */
    protected function parseDefined($key)
    {
        $this->defined[] = $key;
    }

    /**
     * Parse the set of required values.
     *
     * @since 0.1.0
     *
     * @param string $key  The key of the schema data.
     * @param mixed  $data The data associated with the key.
     */
    protected function parseRequired($key, $data)
    {
        if ($this->isTruthy($data)) {
            $this->required[] = $key;
        }
    }

    /**
     * Parse the set of default values.
     *
     * @since 0.1.0
     *
     * @param string $key  The key of the schema data.
     * @param mixed  $data The data associated with the key.
     */
    protected function parseDefault($key, $data)
    {
        $this->defaults[$key] = $data;
    }

    /**
     * Return a boolean true or false for an arbitrary set of data. Recognizes
     * several different string values that should be valued as true.
     *
     * @since 0.1.0
     *
     * @param mixed $data The data to evaluate.
     *
     * @return bool
     */
    protected function isTruthy($data)
    {
        $truthy_values = [
            true,
            1,
            'true',
            'True',
            'TRUE',
            'y',
            'Y',
            'yes',
            'Yes',
            'YES',
            '√',
        ];

        return in_array($data, $truthy_values, true);
    }
}

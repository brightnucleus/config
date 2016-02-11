<?php
/**
 * Config Test
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use BrightNucleus\Config\Config;

/**
 * Class ConfigTest
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /*
     * TODO: There's still lots of work to do to render these tests useful.
     */

    /*
     * Don't use an array const to avoid bumping PHP requirement to 5.6.
     */
    protected static $test_array = [
        'random_string'    => 'test_value',
        'positive_integer' => 42,
        'negative_integer' => -256,
        'positive_boolean' => true,
        'negative_boolean' => false,
    ];

    /**
     * Test creation and value retrieval.
     *
     * @covers BrightNucleus\Config\AbstractConfig::__construct
     * @covers BrightNucleus\Config\Config::__construct
     *
     * @since  1.0.0
     */
    public function testConfigFileCreation()
    {
        $config = new Config(ConfigTest::$test_array);

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface',
            $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig',
            $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
    }

    /**
     * Test the different error conditions that can throw exceptions in
     * __construct().
     *
     * @covers       BrightNucleus\Config\AbstractConfig::__construct
     * @covers       BrightNucleus\Config\Config::__construct
     * @covers       BrightNucleus\Config\Config::fetchArrayData
     *
     * @dataProvider configExceptionsDataProvider
     *
     * @since        1.0.0
     *
     * @param string $exception Exception class to expect.
     * @param string $message   Exception message to expect.
     * @param mixed  $config    Configuration source.
     * @param mixed  $defaults  Default values.
     * @param mixed  $validator Validator object.
     */
    public function testConfigExceptions(
        $exception,
        $message,
        $config,
        $defaults = null,
        $validator = null
    ) {
        $this->setExpectedException($exception, $message);
        $config = new Config($config, $defaults, $validator);
    }

    /**
     * Provide testable data to the testFeatureSupport() method.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function configExceptionsDataProvider()
    {
        return [
            // $exception, $message, $config, $defaults, $validator
            ['InvalidArgumentException', 'Invalid configuration source', null],
            [
                'RuntimeException',
                'Non-existing configuration source',
                '/folder/missing_file.php',
            ],
            [
                'RuntimeException',
                'File inclusion did not return an array.',
                __DIR__ . '/fixtures/dummy_file.txt',
            ],
        ];
    }

    /**
     * @covers BrightNucleus\Config\Config::__construct
     * @covers BrightNucleus\Config\Config::isValid
     */
    public function testValidation()
    {
        $unvalidated_config = new Config(ConfigTest::$test_array, null, null);
        $this->assertTrue($unvalidated_config->isValid());

        $true_validator = $this->getMockBuilder('\BrightNucleus\Config\ConfigValidatorInterface')
                               ->getMock();
        $true_validator->method('isValid')
                       ->willReturn(true);
        $valid_config = new Config(ConfigTest::$test_array, null,
            $true_validator);
        $this->assertTrue($valid_config->isValid());

        $false_validator = $this->getMockBuilder('\BrightNucleus\Config\ConfigValidatorInterface')
                                ->getMock();
        $false_validator->method('isValid')
                        ->willReturn(false);
        $this->setExpectedException('UnexpectedValueException',
            'ConfigInterface file is not valid');
        $invalid_config = new Config(ConfigTest::$test_array, null,
            $false_validator);
    }

    /**
     * @covers BrightNucleus\Config\AbstractConfig::hasKey
     */
    public function testHasKey()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertTrue($config->hasKey('random_string'));
        $this->assertTrue($config->hasKey('positive_integer'));
        $this->assertTrue($config->hasKey('negative_integer'));
        $this->assertTrue($config->hasKey('positive_boolean'));
        $this->assertTrue($config->hasKey('negative_boolean'));
        $this->assertFalse($config->hasKey('some_other_key'));
    }

    /**
     * @covers BrightNucleus\Config\AbstractConfig::getKeys
     */
    public function testGetKeys()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertEquals(array_keys(ConfigTest::$test_array),
            $config->getKeys());
    }

    /**
     * @covers BrightNucleus\Config\AbstractConfig::getKey
     */
    public function testGetKey()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertEquals('test_value', $config->getKey('random_string'));
        $this->assertEquals(42, $config->getKey('positive_integer'));
        $this->assertEquals(-256, $config->getKey('negative_integer'));
        $this->assertTrue($config->getKey('positive_boolean'));
        $this->assertFalse($config->getKey('negative_boolean'));
        $this->setExpectedException('OutOfRangeException',
            'The configuration key some_other_key does not exist.');
        $this->assertFalse($config->getKey('some_other_key'));
    }

    /**
     * @covers BrightNucleus\Config\AbstractConfig::getAll
     */
    public function testGetAll()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertEquals(ConfigTest::$test_array, $config->getAll());
    }

    /**
     * @covers BrightNucleus\Config\Config::__construct
     * @covers BrightNucleus\Config\Config::fetchArrayData
     * @covers BrightNucleus\Config\Config::resolveOptions
     * @covers BrightNucleus\Config\Config::configureOptions
     */
    public function testConfigFileWithoutDefaults()
    {
        $config = new Config(__DIR__ . '/fixtures/config_file.php');
        $this->assertTrue($config->hasKey('random_string'));
        $this->assertTrue($config->hasKey('positive_integer'));
        $this->assertTrue($config->hasKey('negative_integer'));
        $this->assertTrue($config->hasKey('positive_boolean'));
        $this->assertTrue($config->hasKey('negative_boolean'));
        $this->assertFalse($config->hasKey('some_other_key'));
        $this->assertEquals('test_value', $config->getKey('random_string'));
        $this->assertEquals(42, $config->getKey('positive_integer'));
        $this->assertEquals(-256, $config->getKey('negative_integer'));
        $this->assertTrue($config->getKey('positive_boolean'));
        $this->assertFalse($config->getKey('negative_boolean'));
        $this->setExpectedException('OutOfRangeException',
            'The configuration key some_other_key does not exist.');
        $this->assertFalse($config->getKey('some_other_key'));
    }

    /**
     * @covers BrightNucleus\Config\Config::__construct
     * @covers BrightNucleus\Config\Config::fetchArrayData
     * @covers BrightNucleus\Config\Config::resolveOptions
     * @covers BrightNucleus\Config\Config::configureOptions
     */
    public function testConfigFileWithMissingKeys()
    {
        $schema = new ConfigSchema(new Config(__DIR__ . '/fixtures/schema_config_file.php'));
        $this->setExpectedException(
            'UnexpectedValueException',
            'Error while resolving config options: The required option "negative_integer" is missing.'
        );
        $config = new Config([], $schema);
    }

    /**
     * @covers BrightNucleus\Config\Config::__construct
     * @covers BrightNucleus\Config\Config::fetchArrayData
     * @covers BrightNucleus\Config\Config::resolveOptions
     * @covers BrightNucleus\Config\Config::configureOptions
     */
    public function testConfigFileWithDefaults()
    {
        $schema = new ConfigSchema(new Config(__DIR__ . '/fixtures/schema_config_file.php'));
        $config = new Config(['negative_integer' => -333], $schema);
        $this->assertTrue($config->hasKey('random_string'));
        $this->assertTrue($config->hasKey('positive_integer'));
        $this->assertTrue($config->hasKey('negative_integer'));
        $this->assertTrue($config->hasKey('positive_boolean'));
        $this->assertFalse($config->hasKey('negative_boolean'));
        $this->assertFalse($config->hasKey('some_other_key'));
        $this->assertEquals('default_test_value',
            $config->getKey('random_string'));
        $this->assertEquals(99, $config->getKey('positive_integer'));
        $this->assertEquals(-333, $config->getKey('negative_integer'));
        $this->assertTrue($config->getKey('positive_boolean'));
        $this->setExpectedException('OutOfRangeException',
            'The configuration key some_other_key does not exist.');
        $this->assertFalse($config->getKey('some_other_key'));
    }
}

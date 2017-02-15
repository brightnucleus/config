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

    protected static $test_multi_array = [
        'level1' => [
            'level2' => [
                'level3' => [
                    'level4_key' => 'level4_value',
                ],
            ],
        ],
    ];

    /**
     * Test creation and value retrieval.
     *
     * @covers \BrightNucleus\Config\AbstractConfig::__construct
     * @covers \BrightNucleus\Config\Config::__construct
     *
     * @since  1.0.0
     */
    public function testConfigFileCreation()
    {
        $config = new Config(ConfigTest::$test_array);

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
    }

    /**
     * Test the different error conditions that can throw exceptions in
     * __construct().
     *
     * @covers       BrightNucleus\Config\AbstractConfig::__construct
     * @covers       BrightNucleus\Config\Config::__construct
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
        new Config($config, $defaults, $validator);
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
            [
                'BrightNucleus\Config\Exception\InvalidConfigurationSourceException',
                'Invalid configuration source',
                null,
            ],
            [
                'BrightNucleus\Config\Exception\FailedToLoadConfigException',
                'Could not load resource located at "/folder/missing_file.php". Reason: '
                . '"The requested PHP config file "/folder/missing_file.php" does not exist or is not readable.".',
                '/folder/missing_file.php',
            ],
            [
                'BrightNucleus\Config\Exception\FailedToLoadConfigException',
                'Could not find a suitable loader for URI',
                __DIR__ . '/fixtures/dummy_file.txt',
            ],
        ];
    }

    /**
     * @covers \BrightNucleus\Config\Config::__construct
     * @covers \BrightNucleus\Config\Config::isValid
     */
    public function testValidation()
    {
        $unvalidated_config = new Config(ConfigTest::$test_array, null, null);
        $this->assertTrue($unvalidated_config->isValid());

        $true_validator = $this->getMockBuilder('\BrightNucleus\Config\ConfigValidatorInterface')
                               ->getMock();
        $true_validator->method('isValid')
                       ->willReturn(true);
        $valid_config = new Config(ConfigTest::$test_array, null, $true_validator);
        $this->assertTrue($valid_config->isValid());

        $false_validator = $this->getMockBuilder('\BrightNucleus\Config\ConfigValidatorInterface')
                                ->getMock();
        $false_validator->method('isValid')
                        ->willReturn(false);
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\InvalidConfigException',
            'ConfigInterface file is not valid'
        );
        new Config(ConfigTest::$test_array, null, $false_validator);
    }

    /**
     * @covers \BrightNucleus\Config\AbstractConfig::hasKey
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
     * @covers \BrightNucleus\Config\AbstractConfig::__construct
     * @covers \BrightNucleus\Config\AbstractConfig::hasKey
     */
    public function testHasKeyWithMultipleLevels()
    {
        $config = new Config(ConfigTest::$test_multi_array);
        $this->assertTrue($config->hasKey('level1'));
        $this->assertTrue($config->hasKey('level1', 'level2'));
        $this->assertTrue($config->hasKey('level1', 'level2', 'level3'));
        $this->assertTrue($config->hasKey('level1', 'level2', 'level3', 'level4_key'));
        $this->assertTrue($config->hasKey(['level1', 'level2', 'level3', 'level4_key']));
        $this->assertTrue($config->hasKey('level1\level2', 'level3', 'level4_key'));
        $this->assertTrue($config->hasKey('level1\level2', ['level3', 'level4_key']));
        $this->assertTrue($config->hasKey('level1', 'level2/level3', 'level4_key'));
        $this->assertTrue($config->hasKey('level1', 'level2', 'level3.level4_key'));
        $this->assertTrue($config->hasKey('level1\level2\level3\level4_key'));
        $this->assertTrue($config->hasKey('level1/level2/level3/level4_key'));
        $this->assertTrue($config->hasKey('level1.level2.level3.level4_key'));
        $this->assertTrue($config->hasKey('level1\level2/level3.level4_key'));
        $this->assertTrue($config->hasKey(['level1\level2'], ['level3.level4_key']));
        $this->assertTrue($config->hasKey(['level1\level2/level3.level4_key']));
        $this->assertFalse($config->hasKey('level2'));
        $this->assertFalse($config->hasKey('level1', 'level3'));
        $this->assertFalse($config->hasKey('level1', 'level2', 'level4_key'));
        $this->assertFalse($config->hasKey('level0', 'level1', 'level2', 'level3', 'level4_key'));
        $this->assertFalse($config->hasKey('level1.level3'));
        $this->assertFalse($config->hasKey('level1.level2.level4_key'));
        $this->assertFalse($config->hasKey('level0.level1.level2.level3.level4_key'));

        $config = new Config(ConfigTest::$test_multi_array, null, null, ['@', ':', ';']);
        $this->assertTrue($config->hasKey('level1'));
        $this->assertTrue($config->hasKey('level1', 'level2'));
        $this->assertTrue($config->hasKey('level1', 'level2', 'level3'));
        $this->assertTrue($config->hasKey('level1', 'level2', 'level3', 'level4_key'));
        $this->assertTrue($config->hasKey('level1@level2', 'level3', 'level4_key'));
        $this->assertTrue($config->hasKey('level1', 'level2:level3', 'level4_key'));
        $this->assertTrue($config->hasKey('level1', 'level2', 'level3;level4_key'));
        $this->assertTrue($config->hasKey('level1@level2@level3@level4_key'));
        $this->assertTrue($config->hasKey('level1:level2:level3:level4_key'));
        $this->assertTrue($config->hasKey('level1;level2;level3;level4_key'));
        $this->assertTrue($config->hasKey('level1@level2:level3;level4_key'));
    }

    /**
     * @covers \BrightNucleus\Config\AbstractConfig::getKeys
     */
    public function testGetKeys()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertEquals(array_keys(ConfigTest::$test_array), $config->getKeys());
    }

    /**
     * @covers \BrightNucleus\Config\AbstractConfig::getKey
     * @covers \BrightNucleus\Config\AbstractConfig::getKeyArguments
     */
    public function testGetKey()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertEquals('test_value', $config->getKey('random_string'));
        $this->assertEquals(42, $config->getKey('positive_integer'));
        $this->assertEquals(-256, $config->getKey('negative_integer'));
        $this->assertTrue($config->getKey('positive_boolean'));
        $this->assertFalse($config->getKey('negative_boolean'));
    }

    /**
     * @covers \BrightNucleus\Config\AbstractConfig::getKeyArguments
     */
    public function testGetKeyThrowsExceptionOnWrongKey()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\KeyNotFoundException',
            'The configuration key some_other_key does not exist.'
        );
        $config->getKey('some_other_key');
    }

    /**
     * @covers \BrightNucleus\Config\AbstractConfig::getKey
     * @covers \BrightNucleus\Config\AbstractConfig::getKeyArguments
     * @covers \BrightNucleus\Config\AbstractConfig::parseKeysString
     */
    public function testGetKeyWithMultipleLevels()
    {
        $config = new Config(ConfigTest::$test_multi_array);
        $this->assertEquals(['level2' => ['level3' => ['level4_key' => 'level4_value']]], $config->getKey('level1'));
        $this->assertEquals(['level3' => ['level4_key' => 'level4_value']], $config->getKey('level1', 'level2'));
        $this->assertEquals(['level4_key' => 'level4_value'], $config->getKey('level1', 'level2', 'level3'));
        $this->assertEquals('level4_value', $config->getKey('level1', 'level2', 'level3', 'level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1\level2', 'level3', 'level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1', 'level2/level3', 'level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1', 'level2', 'level3.level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1\level2\level3\level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1/level2/level3/level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1.level2.level3.level4_key'));
        $this->assertEquals('level4_value', $config->getKey('level1\level2/level3.level4_key'));
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\KeyNotFoundException',
            'The configuration key level1->level2->level4_key does not exist.'
        );
        $config->getKey('level1', 'level2', 'level4_key');
    }

    /**
     * @covers \BrightNucleus\Config\AbstractConfig::getAll
     */
    public function testGetAll()
    {
        $config = new Config(ConfigTest::$test_array);
        $this->assertEquals(ConfigTest::$test_array, $config->getAll());
    }

    /**
     * @covers \BrightNucleus\Config\Config::__construct
     * @covers \BrightNucleus\Config\Config::resolveOptions
     * @covers \BrightNucleus\Config\Config::configureOptions
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
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\KeyNotFoundException',
            'The configuration key some_other_key does not exist.'
        );
        $this->assertFalse($config->getKey('some_other_key'));
    }

    /**
     * @covers \BrightNucleus\Config\Config::__construct
     * @covers \BrightNucleus\Config\Config::resolveOptions
     * @covers \BrightNucleus\Config\Config::configureOptions
     */
    public function testConfigFileWithMissingKeys()
    {
        $schema = new ConfigSchema(new Config(__DIR__ . '/fixtures/schema_config_file.php'));
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\FailedToResolveConfigException',
            'Error while resolving config options: The required option "negative_integer" is missing.'
        );
        new Config([], $schema);
    }

    /**
     * @covers \BrightNucleus\Config\Config::__construct
     * @covers \BrightNucleus\Config\Config::resolveOptions
     * @covers \BrightNucleus\Config\Config::configureOptions
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
        $this->assertEquals('default_test_value', $config->getKey('random_string'));
        $this->assertEquals(99, $config->getKey('positive_integer'));
        $this->assertEquals(-333, $config->getKey('negative_integer'));
        $this->assertTrue($config->getKey('positive_boolean'));
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\KeyNotFoundException',
            'The configuration key some_other_key does not exist.'
        );
        $this->assertFalse($config->getKey('some_other_key'));
    }

    public function testGetSubConfig()
    {
        $config      = new Config(__DIR__ . '/fixtures/deep_config_file.php');
        $subconfig   = $config->getSubConfig('vendor/package');
        $subsection1 = $config->getSubConfig('vendor', 'package', 'section_1');
        $subsection2 = $subconfig->getSubConfig('section_2');
        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $subconfig);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $subconfig);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $subconfig);
        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $subsection1);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $subsection1);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $subsection1);
        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $subsection2);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $subsection2);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $subsection2);
        $this->assertTrue($config->hasKey('vendor/package'));
        $this->assertTrue($config->hasKey('vendor/package/section_1/test_key_1'));
        $this->assertTrue($config->hasKey('vendor/package/section_2/test_key_2'));
        $this->assertTrue($subconfig->hasKey('section_1/test_key_1'));
        $this->assertTrue($subconfig->hasKey('section_2/test_key_2'));
        $this->assertTrue($subsection1->hasKey('test_key_1'));
        $this->assertTrue($subsection2->hasKey('test_key_2'));
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\KeyNotFoundException',
            'The configuration key some_other_key does not exist.'
        );
        $this->assertFalse($config->getSubConfig('some_other_key'));
    }
}

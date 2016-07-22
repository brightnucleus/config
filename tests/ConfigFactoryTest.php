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

use org\bovigo\vfs\vfsStream;

/**
 * Class ConfigFactoryTest.
 *
 * @since   0.3.0
 *
 * @package BrightNucleus\Config
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
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
     * Test creation from file.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::createFromFile
     *
     * @since  0.3.0
     */
    public function testCreateFromFile()
    {
        $config = ConfigFactory::createFromFile(__DIR__ . '/fixtures/config_file.php');

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('random_string'));

        $config2 = ConfigFactory::createFromFile(
            'nonsense_file.php',
            'still_nonsense.txt',
            __DIR__ . '/fixtures/config_file.php'
        );

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config2);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config2);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config2);
        $this->assertTrue($config2->hasKey('random_string'));
    }

    /**
     * Test creation from array.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::createFromArray
     *
     * @since  0.3.0
     */
    public function testCreateFromArray()
    {
        $config = ConfigFactory::createFromArray(['some_key' => 'some_value']);

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('some_key'));
        $this->assertEquals('some_value', $config->getKey('some_key'));
    }

    /**
     * Test creation from best guess using one file.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::create
     *
     * @since  0.3.0
     */
    public function testCreateFromBestGuessUsingOneFile()
    {
        $config = ConfigFactory::create(__DIR__ . '/fixtures/config_file.php');

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('random_string'));
    }

    /**
     * Test creation from best guess using several files.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::create
     *
     * @since  0.3.0
     */
    public function testCreateFromBestGuessUsingSeveralFiles()
    {
        $config = ConfigFactory::create(
            'nonsense_file.php',
            'still_nonsense.txt',
            __DIR__ . '/fixtures/config_file.php'
        );

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('random_string'));
    }

    /**
     * Test creation from best guess using an array.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::create
     *
     * @since  0.3.0
     */
    public function testCreateFromBestGuessUsingAnArray()
    {
        $config = ConfigFactory::create(['some_key' => 'some_value']);

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('some_key'));
        $this->assertEquals('some_value', $config->getKey('some_key'));
    }

    /**
     * Test whether the caching system works when loading the same config file several times.
     *
     * @since 0.4.3
     */
    public function testWhetherCachingWorks()
    {
        $content       = '<?php return [ "test_key" => "test_value" ];';
        $empty_content = '<?php return [];';
        $filesystem    = vfsStream::setup();
        $configFile    = vfsStream::newFile('test_config.php')
                                  ->withContent($content);
        $filesystem->addChild($configFile);
        $this->assertTrue($filesystem->hasChild('test_config.php'));
        $this->assertEquals($content, $configFile->getContent());

        $configA = ConfigFactory::create($configFile->url());
        $this->assertTrue($configA->hasKey('test_key'));

        $configFile->setContent($empty_content);
        $this->assertEquals($empty_content, $configFile->getContent());

        $configB = ConfigFactory::create($configFile->url());
        $this->assertTrue($configB->hasKey('test_key'));
    }

    /**
     * Test whether a subconfig can be immediately created through the ConfigFactory.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::createSubConfig
     *
     * @since  0.4.5
     */
    public function testCreateSubConfig()
    {
        $config = ConfigFactory::createSubConfig(__DIR__ . '/fixtures/deep_config_file.php', 'vendor', 'package');

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('section_1', 'test_key_1'));
    }

    /**
     * Test merging using several files.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::merge
     *
     * @since  0.3.0
     */
    public function testMergeUsingSeveralFiles()
    {
        $config = ConfigFactory::merge(
            __DIR__ . '/fixtures/config_file.php',
            __DIR__ . '/fixtures/override_config_file.php'
        );

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('random_string'));
        $this->assertEquals('override_value', $config->getKey('random_string'));
    }

    /**
     * Test merging using several files.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::merge
     *
     * @since  0.3.0
     */
    public function testMergeUsingHierarchicalData()
    {
        $config = ConfigFactory::merge(
            __DIR__ . '/fixtures/deep_config_file.php',
            __DIR__ . '/fixtures/override_deep_config_file.php'
        )->getSubConfig('vendor\package');

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('section_1', 'test_key_1'));
        $this->assertTrue($config->hasKey('section_2', 'test_key_2'));
        $this->assertTrue($config->hasKey('section_1', 'test_key_3'));
        $this->assertTrue($config->hasKey('section_3', 'test_key_4'));
        $this->assertEquals('test_value_1', $config->getKey('section_1', 'test_key_1'));
        $this->assertEquals('override_value_2', $config->getKey('section_2', 'test_key_2'));
        $this->assertEquals('override_value_3', $config->getKey('section_1', 'test_key_3'));
        $this->assertEquals('override_value_4', $config->getKey('section_3', 'test_key_4'));
    }

    /**
     * Test merging using invalid files.
     *
     * @covers \BrightNucleus\Config\ConfigFactory::merge
     *
     * @since  0.3.0
     */
    public function testMergeUsingInvalidFiles()
    {
        $config = ConfigFactory::merge(
            'nonsense_file.php',
            'still_nonsense.txt',
            __DIR__ . '/fixtures/config_file.php'
        );

        $this->assertInstanceOf('\BrightNucleus\Config\ConfigInterface', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\AbstractConfig', $config);
        $this->assertInstanceOf('\BrightNucleus\Config\Config', $config);
        $this->assertTrue($config->hasKey('random_string'));
    }
}

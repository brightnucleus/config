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

class ConfigTraitTest extends \PHPUnit_Framework_TestCase
{

    use ConfigTrait;

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::processConfig
     */
    public function testProcessConfig()
    {
        $this->assertNull($this->config);
        $this->processConfig(new Config([]));
        $this->assertNotNull($this->config);
        $this->assertInstanceOf(
            'BrightNucleus\Config\ConfigInterface',
            $this->config
        );
        unset($this->config);
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::hasConfigKey
     */
    public function testHasConfigKey()
    {
        $this->processConfig(
            new Config(
                [
                    'testkey1' => 'testvalue1',
                    'testkey2' => 'testvalue2',
                ]
            )
        );
        $this->assertTrue($this->hasConfigKey('testkey1'));
        $this->assertTrue($this->hasConfigKey('testkey2'));
        $this->assertFalse($this->hasConfigKey('testkey3'));
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::hasConfigKey
     */
    public function testHasConfigKeyWithMultipleLevels()
    {
        $this->processConfig(
            new Config(
                [
                    'level1' => ['level2' => ['level3' => ['level4_key' => 'level4_value'],],],
                ]
            )
        );
        $this->assertTrue($this->hasConfigKey('level1'));
        $this->assertTrue($this->hasConfigKey('level1', 'level2'));
        $this->assertTrue($this->hasConfigKey('level1', 'level2', 'level3'));
        $this->assertTrue($this->hasConfigKey('level1', 'level2', 'level3', 'level4_key'));
        $this->assertTrue($this->hasConfigKey('level1\level2', 'level3', 'level4_key'));
        $this->assertTrue($this->hasConfigKey('level1', 'level2/level3', 'level4_key'));
        $this->assertTrue($this->hasConfigKey('level1', 'level2', 'level3.level4_key'));
        $this->assertTrue($this->hasConfigKey('level1\level2\level3\level4_key'));
        $this->assertTrue($this->hasConfigKey('level1/level2/level3/level4_key'));
        $this->assertTrue($this->hasConfigKey('level1.level2.level3.level4_key'));
        $this->assertTrue($this->hasConfigKey('level1\level2/level3.level4_key'));
        $this->assertFalse($this->hasConfigKey('level1', 'level2', 'level4_key'));
        $this->assertFalse($this->hasConfigKey('level1', 'level3'));
        $this->assertFalse($this->hasConfigKey('level2'));
        $this->assertFalse($this->hasConfigKey('some_other_key'));
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::getConfigKey
     */
    public function testGetConfigKey()
    {
        $this->processConfig(
            new Config(
                [
                    'testkey1' => 'testvalue1',
                    'testkey2' => 'testvalue2',
                ]
            )
        );
        $this->assertEquals('testvalue1', $this->getConfigKey('testkey1'));
        $this->assertEquals('testvalue2', $this->getConfigKey('testkey2'));
        $this->setExpectedException('OutOfRangeException');
        $this->getConfigKey('testkey3');
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::getConfigKey
     */
    public function testGetConfigKeyWithMultipleLevels()
    {
        $this->processConfig(
            new Config(
                [
                    'level1' => ['level2' => ['level3' => ['level4_key' => 'level4_value'],],],
                ]
            )
        );
        $this->assertEquals('level4_value', $this->getConfigKey('level1', 'level2', 'level3', 'level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1\level2', 'level3', 'level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1', 'level2/level3', 'level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1', 'level2', 'level3.level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1\level2\level3\level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1/level2/level3/level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1.level2.level3.level4_key'));
        $this->assertEquals('level4_value', $this->getConfigKey('level1\level2/level3.level4_key'));
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\KeyNotFoundException',
            'The configuration key level1->level2->level4_key does not exist.'
        );
        $this->getConfigKey('level1', 'level2', 'level4_key');
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::getConfigArray
     */
    public function testGetConfigArray()
    {
        $this->processConfig(
            new Config(
                [
                    'testkey1' => 'testvalue1',
                    'testkey2' => 'testvalue2',
                ]
            )
        );
        $this->assertEquals(
            ['testkey1' => 'testvalue1', 'testkey2' => 'testvalue2'],
            $this->getConfigArray()
        );
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::getConfigKeys
     */
    public function testGetConfigKeys()
    {
        $this->processConfig(
            new Config(
                [
                    'testkey1' => 'testvalue1',
                    'testkey2' => 'testvalue2',
                ]
            )
        );
        $this->assertEquals(['testkey1', 'testkey2'], $this->getConfigKeys());
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::processConfig
     */
    public function testProcessConfigAllowsPreKeying()
    {
        $this->processConfig(
            new Config(
                [
                    'vendor' => [
                        'package' => [
                            'testkey1' => 'testvalue1',
                            'testkey2' => 'testvalue2',
                        ],
                    ],
                ]
            ),
            'vendor\package'
        );
        $this->assertEquals(
            ['testkey1' => 'testvalue1', 'testkey2' => 'testvalue2'],
            $this->getConfigArray()
        );
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::processConfig
     */
    public function testProcessConfigThrowsException()
    {
        $this->setExpectedException(
            'BrightNucleus\Config\Exception\FailedToProcessConfigException',
            'Could not process the config with the arguments'
        );
        $this->processConfig(
            new Config(
                [
                    'testkey1' => 'testvalue1',
                    'testkey2' => 'testvalue2',
                ]
            ),
            'vendor\package'
        );
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::fetchConfig
     */
    public function testFetchConfigWithExistingFile()
    {
        $config = $this->fetchConfig(__DIR__ . '/fixtures/config_file.php');
        $this->assertNotNull($config);
        $this->assertInstanceOf(
            'BrightNucleus\Config\ConfigInterface',
            $config
        );
        $this->assertTrue($config->hasKey('random_string'));
        $this->assertTrue($config->hasKey('positive_integer'));
        $this->assertTrue($config->hasKey('negative_integer'));
        $this->assertTrue($config->hasKey('positive_boolean'));
        $this->assertTrue($config->hasKey('negative_boolean'));
        $this->assertFalse($config->hasKey('some_other_key'));
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::fetchConfig
     */
    public function testFetchConfigWithNonExistingFile()
    {
        $config = $this->fetchConfig(__DIR__ . '/fixtures/file_does_not_exist.php');
        $this->assertNotNull($config);
        $this->assertInstanceOf(
            'BrightNucleus\Config\ConfigInterface',
            $config
        );
        $this->assertEquals([], $config->getArrayCopy());
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::fetchDefaultConfig
     */
    public function testFetchDefaultConfig()
    {
        $config = $this->fetchDefaultConfig();
        $this->assertNotNull($config);
        $this->assertInstanceOf(
            'BrightNucleus\Config\ConfigInterface',
            $config
        );
        $this->assertTrue($config->hasKey('random_string'));
        $this->assertTrue($config->hasKey('positive_integer'));
        $this->assertTrue($config->hasKey('negative_integer'));
        $this->assertTrue($config->hasKey('positive_boolean'));
        $this->assertTrue($config->hasKey('negative_boolean'));
        $this->assertFalse($config->hasKey('some_other_key'));
    }

    // Used to provide a file name for fetchDefaultConfig().
    protected function getDefaultConfigFile()
    {
        return __DIR__ . '/fixtures/config_file.php';
    }

    public function testGetConfigCallable()
    {
        $this->processConfig(
            new Config(
                [
                    'testkey1'     => function ($a, $b, $c) {
                        return "${c}-${b}-${a}";
                    },
                    'testkey2'     => 'testvalue2',
                    'nestedlevel0' => [
                        'nestedlevel1' => [
                            'nestedlevel2' => [
                                'testkey3' => function ($a, $b, $c) {
                                    return "${c}.${b}.${a}";
                                },
                            ],
                        ],
                    ],
                    'testkey4'     => function ($a = null, $b = null, $c = null) {
                        return "${c}-${b}-${a}";
                    },
                ]
            )
        );

        $this->assertEquals('3-2-1', $this->getConfigCallable('testkey1', [1, 2, 3]));
        $this->assertEquals('testvalue2', $this->getConfigCallable('testkey2', [1, 2, 3]));
        $this->assertEquals(
            '3.2.1',
            $this->getConfigCallable(
                'nestedlevel0/nestedlevel1\nestedlevel2.testkey3',
                [1, 2, 3]
            )
        );
        $this->assertEquals(
            '3.2.1',
            $this->getConfigCallable(
                ['nestedlevel0', 'nestedlevel1', 'nestedlevel2\testkey3'],
                [1, 2, 3]
            )
        );
        $this->assertEquals('--', $this->getConfigCallable('testkey4', []));
        $this->assertEquals('--', $this->getConfigCallable('testkey4'));
    }
}

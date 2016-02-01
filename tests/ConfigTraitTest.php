<?php
/**
 * Config Trait Test
 *
 * @package   BrightNucleus\Config
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.brightnucleus.com/
 * @copyright 2016 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\Config;

use BrightNucleus\Config\Config;
use BrightNucleus\Config\ConfigTrait;

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
        $this->assertInstanceOf('BrightNucleus\Config\ConfigInterface',
            $this->config);
        unset($this->config);
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::hasConfigKey
     */
    public function testHasKey()
    {
        $this->processConfig(new Config([
            'testkey1' => 'testvalue1',
            'testkey2' => 'testvalue2',
        ]));
        $this->assertTrue($this->hasConfigKey('testkey1'));
        $this->assertTrue($this->hasConfigKey('testkey2'));
        $this->assertFalse($this->hasConfigKey('testkey3'));
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::getConfigKey
     */
    public function testGetKey()
    {
        $this->processConfig(new Config([
            'testkey1' => 'testvalue1',
            'testkey2' => 'testvalue2',
        ]));
        $this->assertEquals('testvalue1', $this->getConfigKey('testkey1'));
        $this->assertEquals('testvalue2', $this->getConfigKey('testkey2'));
        $this->setExpectedException('OutOfRangeException');
        $this->getConfigKey('testkey3');
    }

    /**
     * @covers \BrightNucleus\Config\ConfigTrait::getConfigKeys
     */
    public function testGetKeys()
    {
        $this->processConfig(new Config([
            'testkey1' => 'testvalue1',
            'testkey2' => 'testvalue2',
        ]));
        $this->assertEquals(['testkey1', 'testkey2'], $this->getConfigKeys());
    }
}

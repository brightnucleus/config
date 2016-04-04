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

class ConfigSchemaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BrightNucleus\Config\ConfigSchema::__construct
     */
    public function testInstantiation()
    {
        $schema_config = $this->getMockBuilder('\BrightNucleus\Config\ConfigInterface')
                              ->getMock();
        $schema_config->method('getArrayCopy')
                      ->willReturn([]);
        $schema = new ConfigSchema($schema_config);
        $this->assertInstanceOf(
            '\BrightNucleus\Config\ConfigSchemaInterface',
            $schema
        );
        $this->assertInstanceOf(
            '\BrightNucleus\Config\AbstractConfigSchema',
            $schema
        );
        $this->assertInstanceOf('\BrightNucleus\Config\ConfigSchema', $schema);
        $this->setExpectedException(
            'BrightNucleus\Exception\InvalidArgumentException',
            'Invalid schema source:'
        );
        new ConfigSchema(25);
    }

    /**
     * @covers \BrightNucleus\Config\ConfigSchema::parseSchema
     * @covers \BrightNucleus\Config\ConfigSchema::parseDefined
     * @covers \BrightNucleus\Config\ConfigSchema::parseDefault
     * @covers \BrightNucleus\Config\ConfigSchema::parseRequired
     * @covers \BrightNucleus\Config\ConfigSchema::isTruthy
     */
    public function testParsing()
    {
        $schema_config = new Config(__DIR__ . '/fixtures/schema_config_file.php');
        $schema        = new ConfigSchema($schema_config);
        $this->assertInstanceOf(
            '\BrightNucleus\Config\ConfigSchemaInterface',
            $schema
        );
        $this->assertInstanceOf(
            '\BrightNucleus\Config\AbstractConfigSchema',
            $schema
        );
        $this->assertInstanceOf('\BrightNucleus\Config\ConfigSchema', $schema);
    }
}

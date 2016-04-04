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

$test_data = [
    'section_1' => [
        'test_key_1' => 'test_value_1',
    ],
    'section_2' => [
        'test_key_2' => 'test_value_2',
    ],
];

return ['vendor' => ['package' => $test_data]];

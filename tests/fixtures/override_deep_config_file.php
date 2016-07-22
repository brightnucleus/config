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
        'test_key_3' => 'override_value_3',
    ],
    'section_2' => [
        'test_key_2' => 'override_value_2',
    ],
    'section_3' => [
        'test_key_4' => 'override_value_4',
    ],
];

return ['vendor' => ['package' => $test_data]];

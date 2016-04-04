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

namespace BrightNucleus\Core;

$test_schema = [

    'random_string'    => [
        'default'  => 'default_test_value',
        'required' => true,
    ],
    'positive_integer' => [
        'default'  => 99,
        'required' => 'TRUE',
    ],
    'negative_integer' => [
        'required' => 'Yes',
    ],
    'positive_boolean' => [
        'default'  => true,
        'required' => false,
    ],
    'negative_boolean' => [
        'required' => 'No',
    ],

];

return $test_schema;

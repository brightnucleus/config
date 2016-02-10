# Bright Nucleus Config Component

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/brightnucleus/config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/brightnucleus/config/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/brightnucleus/config/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/brightnucleus/config/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/brightnucleus/config/badges/build.png?b=master)](https://scrutinizer-ci.com/g/brightnucleus/config/build-status/master)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/e718c34afc46409b90fd72a107158009)](https://www.codacy.com/app/BrightNucleus/config)
[![Code Climate](https://codeclimate.com/github/brightnucleus/config/badges/gpa.svg)](https://codeclimate.com/github/brightnucleus/config)

[![Latest Stable Version](https://poser.pugx.org/brightnucleus/config/v/stable)](https://packagist.org/packages/brightnucleus/config)
[![Total Downloads](https://poser.pugx.org/brightnucleus/config/downloads)](https://packagist.org/packages/brightnucleus/config)
[![Latest Unstable Version](https://poser.pugx.org/brightnucleus/config/v/unstable)](https://packagist.org/packages/brightnucleus/config)
[![License](https://poser.pugx.org/brightnucleus/config/license)](https://packagist.org/packages/brightnucleus/config)

This is a very lean Config component to help you write reusable code. It only offers basic functionality and is meant to be used in libraries and small projects. If you need a Config component for more complex projects, you should take a look at the [Symfony Config Component](http://symfony.com/doc/current/components/config/index.html).

## Installation

The best way to use this component is through Composer:

```BASH
composer require brightnucleus/config
```

## Usage

A class that wants to be configurable should accept a `ConfigInterface` in its constructor, so that the Config can be injected. The surrounding code then should inject an instance of an object (for example the generic `Config` that is provided with this component). This way, the class that accepts the Config can be written in a 100% reusable way, while all project-specific stuff will be injected through the Config.

See [link to post coming soon] for more details.

## Contributing

All feedback / bug report / pull request is welcome.

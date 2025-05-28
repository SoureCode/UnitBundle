
# sourecode/unit-bundle

[![Packagist Version](https://img.shields.io/packagist/v/sourecode/unit-bundle.svg)](https://packagist.org/packages/sourecode/unit-bundle)
[![Downloads](https://img.shields.io/packagist/dt/sourecode/unit-bundle.svg)](https://packagist.org/packages/sourecode/unit-bundle)
[![CI](https://github.com/SoureCode/UnitBundle/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/SoureCode/UnitBundle/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/SoureCode/UnitBundle/branch/master/graph/badge.svg?token=ZEGEZJEQ1B)](https://codecov.io/gh/SoureCode/UnitBundle)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FSoureCode%2FUnitBundle%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/SoureCode/UnitBundle/master)

This bundle provides a simple way to handle unit of measurements in Symfony applications.

- [License](./LICENSE)

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
composer require sourecode/unit-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require sourecode/unit-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    \SoureCode\Bundle\Unit\SoureCodeUnitBundle::class => ['all' => true],
];
```

## Config

```yaml
# config/packages/soure_code_unit.yaml
# @todo
```
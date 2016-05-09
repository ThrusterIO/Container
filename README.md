# Container Component

[![Latest Version](https://img.shields.io/github/release/ThrusterIO/container.svg?style=flat-square)]
(https://github.com/ThrusterIO/container/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)]
(LICENSE)
[![Build Status](https://img.shields.io/travis/ThrusterIO/container.svg?style=flat-square)]
(https://travis-ci.org/ThrusterIO/container)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ThrusterIO/container.svg?style=flat-square)]
(https://scrutinizer-ci.com/g/ThrusterIO/container)
[![Quality Score](https://img.shields.io/scrutinizer/g/ThrusterIO/container.svg?style=flat-square)]
(https://scrutinizer-ci.com/g/ThrusterIO/container)
[![Total Downloads](https://img.shields.io/packagist/dt/thruster/container.svg?style=flat-square)]
(https://packagist.org/packages/thruster/container)

[![Email](https://img.shields.io/badge/email-team@thruster.io-blue.svg?style=flat-square)]
(mailto:team@thruster.io)

The Thruster Container Component. Container is small self contained dependency injection container implementing `ContainerInterface` interface.


## Install

Via Composer

```bash
$ composer require thruster/container
```

## Usage

Just create instance of Container:

```php
<?php

use Thruster\Component\Container;

$container = new Container();
```

Also you can pass preset array of values to constructor.

```php
<?php

use Thruster\Component\Container;

$values = [
	'render.engine' => function ($container) {
		return new RenderEngine($container->get('translation.engine'));
	},
	'translation.engine' => function () {
		return new TranslationEngine();
	}
];

$container = new Container($values);
```

Container has a bunch of simple named functions to use container: has, set, get, remove:

```php
<?php

use Thruster\Component\Container;

$container = new Container();

$container->has('render.engine'); // = false
$container->set('render.engine', function ($container) {
		return new RenderEngine($container->get('translation.engine'));
});

$container->has('render.engine'); // = true

$renderEngine = $container->get('render.engine');

$container->remove('render.engine');
$container->has('render.engine'); // = false
```


Container also support `\ArrayAccess`

```php
<?php

use Thruster\Component\Container;

$container = new Container();

isset($container['render.engine']); // = false
$container['render.engine'] = function ($container) {
		return new RenderEngine($container->get('translation.engine'));
};

isset($container['render.engine']); // = true

$renderEngine = $container['render.engine'];

unset($container['render.engine']);
isset($container['render.engine']); // = false
```

By default Container always returns the same instance of identifier, but you can create a factory definition which will return always new instance of identifier.

```php
<?php

use Thruster\Component\Container;

$container = new Container();

$i = 1;

$value = function () use ($i) {
	return $i++;
}

$factoryValue = function () use (&$i) {
	return $i++;
}

$container->set('normal', $value);

$container->get('normal'); // = 1
$container->get('normal'); // = 1

$container->set('factory', $factoryValue);

$container->get('factory'); // = 1
$container->get('factory'); // = 2
```

Container provides a way to extend container with `ContainerProviderInterface`

```php
<?php

use Thruster\Component\Container;
use Thruster\Component\ContainerProviderInterface;

$container = new Container();

$provider = new class implements ContainerProviderInterface {
	public function register(Container $container)
	{
		$container->set(....);
	}
};

$container->addProvider($provider);
```

## Errors

* Container throws `NotFoundException` if identifier is not found in container.
* Container throws `IdentifierFrozenException` when trying to set definition for already frozen identifier. (Frozen means when value was already used once)

## Testing

```bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.


## License

Please see [License File](LICENSE) for more information.

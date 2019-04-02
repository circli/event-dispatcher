# Circli Event Dispatcher


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]

The package provides a standard event dispatcher, as well as one null dispatcher that can be used as default dispatcher in the `EventDispatcherAwareInterface`

## Installation

Via Composer

```
$ composer require circli/event-dispatcher
```

## Usage

The dispatcher accepts a `Psr\EventDispatcher\ListenerProviderInterface` to its constructor

Basic example:

```php
use Circli\EventDispatcher\EventDispatcher;
use Circli\EventDispatcher\ListenerProvider\DefaultProvider;

$provider = new DefaultProvider();
$dispatcher = new EventDispatcher($provider);

$provider->listen(Event::class, function(Event $e) {
    // do stuff
});

$dispatcher->dispatch(new Event());
```

## Providers

The package includes a couple of providers for ease of use. You can also find some useful provider in [`fig/event-dispatcher-util`](https://github.com/php-fig/event-dispatcher-util)

### `BasicProvider`

```php
use Circli\EventDispatcher\ListenerProvider\BasicProvider;
$provider = new PriorityProvider();
$provider->listent(Event::class, $listener);
```

### `PriorityProvider`


```php
use Circli\EventDispatcher\ListenerProvider\PriorityProvider;
$provider = new PriorityProvider();

//Add listener with lower than default priority
$provider->listent(Event::class, $listener, 900);

//Add listener with higher than default priority
$provider->listent(Event::class, $listener, 1100);

//Add listener with normal priority
$provider->listent(Event::class, $listener);
```

## `LazyListenerFactory`

We also include a factory class to create lazy loaded handlers.

```php
use Circli\EventDispatcher\LazyListenerFactory;
use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use Psr\Container\ContainerInterface;

$lazyFacytory = new LazyListenerFactory($psr11Container);

$listener = $lazyFactory->lazy('SomeService');
$provider = new DefaultProvider();
$provider->listent(Event::class, $listener);
```

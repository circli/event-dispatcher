# Circli Event Dispatcher

[![Latest Version on Packagist](https://img.shields.io/packagist/v/circli/event-dispatcher.svg)](https://packagist.org/packages/circli/event-dispatcher)
[![Software License](https://img.shields.io/github/license/circli/event-dispatcher.svg)](LICENSE.md)
[![Build Status](https://github.com/circli/event-dispatcher/actions/workflows/unit-test.yml/badge.svg)](https://github.com/circli/event-dispatcher/actions/workflows/unit-test.yml)
[![Coverage Status](https://coveralls.io/repos/github/circli/event-dispatcher/badge.svg?branch=2.x)](https://coveralls.io/github/circli/event-dispatcher?branch=2.x)

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

### `DefaultProvider`

```php
use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
$provider = new DefaultProvider();
$provider->listen(Event::class, $listener);
```

### `PriorityProvider`


```php
use Circli\EventDispatcher\ListenerProvider\PriorityProvider;
$provider = new PriorityProvider();

//Add listener with lower than default priority
$provider->listen(Event::class, $listener, 900);

//Add listener with higher than default priority
$provider->listen(Event::class, $listener, 1100);

//Add listener with normal priority
$provider->listen(Event::class, $listener);
```

### `ContainerListenerProvider`

Use a Psr-11 container to lazy load the callbacks.

```php
use Circli\EventDispatcher\ListenerProvider\ContainerListenerProvider;
$container = new SomePsr11Container();

$provider = new ContainerListenerProvider($container);
$provider->addService(Event::class, EventListener::class);
```

### `PriorityAggregateProvider`

```php
use Circli\EventDispatcher\ListenerProvider\PriorityAggregateProvider;
use Circli\EventDispatcher\ListenerProvider\DefaultProvider;

$aggregateProvider = new PriorityAggregateProvider();
$aggregateProvider->addProvider(new DefaultProvider());

// Add with higher than default priority
$aggregateProvider->addProviderWithPriority(new DefaultProvider(), 1500);

// Add with lower than default priority
$aggregateProvider->addProviderWithPriority(new DefaultProvider(), 500);

```

### `FilterableProvider`

```php
use Circli\EventDispatcher\ListenerProvider\FilterableProvider;

$provider = new FilterableProvider();

$provider->listen(RandomEvent::class, $listener, function ($event) {
    return ifRandomExternalThingIsTrue();
});
```

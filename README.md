# EventDispatcher

Simple event dispatcher

## Install

    composer require circli/event-dispatcher
    
## Usage

```php
class TestEvent {}

$dispatcher = new EventDispatcher();
$dispatcher->listen(TestEvent::class, function(TestEvent $e) {
    //do stuff
});

$dispatcher->tigger(new TestEvent());
```

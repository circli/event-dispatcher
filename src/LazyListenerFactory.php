<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

use Psr\Container\ContainerInterface;

class LazyListenerFactory
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param class-string $listener
     */
    public function lazy($listener): callable
    {
        return function ($event) use ($listener) {
            $callback = $this->container->get($listener);
            if (!\is_callable($callback)) {
                throw new \InvalidArgumentException('Listener not a valid service callback');
            }
            $callback($event);
        };
    }
}

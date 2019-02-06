<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

class LazyListenerFactory
{
    /** @var ListenerResolverInterface */
    private $resolver;

    public function __construct(ListenerResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function lazy($listener): callable
    {
        return function ($event) use ($listener) {
            $callback = $this->resolver->resolve($listener);
            if (\is_callable($callback)) {
                $callback($event);
            }
        };
    }
}

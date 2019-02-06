<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

interface ListenerResolverInterface
{
    public function resolve($listener): callable;
}

<?php

namespace Docfav\Infrastructure\Events;

use Docfav\Domain\EventDispatcherInterface;

class InMemoryEventDispatcher implements EventDispatcherInterface
{

    private array $listeners = [];

    /**
     * @var array $dispatchedEvents Array para almacenar los eventos despachados.
     */
    private array $dispatchedEvents = [];

    public function register(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): void
    {
        $eventClass = get_class($event);
        $this->dispatchedEvents[] = $event;
        if (!isset($this->listeners[$eventClass]))
        {
            return;
        }
        foreach ($this->listeners[$eventClass] as $listener)
        {
            $listener($event);
        }
    }

    /**
     * @return array Devuelve los eventos despachados.
     */
    public function getDispatchedEvents(): array
    {
        return $this->dispatchedEvents;
    }

}

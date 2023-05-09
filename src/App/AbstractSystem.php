<?php

declare(strict_types=1);

namespace App;

use App\EventBusSystem\Event;
use App\EventBusSystem\EventBus;

abstract class AbstractSystem
{

    protected array $events = [] ;

    public function processEvent(EventBus $eventBus): void
    {
        foreach ($eventBus->getEvents() as $event) {
            if (isset($this->events[$event->getType()])) {
                $handlerMethod = $this->events[$event->getType()];
                $this->{$handlerMethod}($event, $eventBus);
            }
        }
    }
}
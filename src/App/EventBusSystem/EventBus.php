<?php
declare(strict_types=1);
namespace App\EventBusSystem;

class EventBus
{
    private array $events = [];

    public function push(Event $event): void
    {
        $this->events[] = $event;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }
}
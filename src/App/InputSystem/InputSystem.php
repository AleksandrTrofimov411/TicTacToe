<?php

declare(strict_types=1);

namespace App\InputSystem;

use App\AbstractSystem;
use App\EventBusSystem\Event;
use App\EventBusSystem\EventBus;
use Exception;

class InputSystem extends AbstractSystem
{

    protected array $events = [
        'inputCoordinate' => 'input'
    ];
    private Keyboard $keyboard;

    public function __construct()
    {
        $this->keyboard = new Keyboard();
    }

    public function input(Event|null $event, EventBus $eventBus): void
    {
        try {
            $x = $this->keyboard->input('Input X coordinate - ');
            $this->isValidMove($x);
            $y = $this->keyboard->input('Input Y coordinate - ');
            $this->isValidMove($y);
            $eventBus->clearEvents();
            $event = new Event('updateGameState');
            $event->setData('x', (int) $x);
            $event->setData('y', (int) $y);
            $eventBus->push($event);
        } catch (Exception $e) {
            $eventBus->clearEvents();
            $event = new Event('displayErrorMessage');
            $event->setData('isError', 0);
            $event->setData('picture', $e->getMessage());
            $event->setData('x', 5);
            $event->setData('y', 20);
            $eventBus->push($event);
        }
    }

    /**
     * @throws Exception
     */
    public function isValidMove(string $userInput): void
    {

        if (!is_numeric($userInput)) {
            throw new Exception('Error! Input is not a number');
        }

    }
}





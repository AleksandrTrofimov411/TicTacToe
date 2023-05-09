<?php
declare(strict_types=1);

namespace App\GameSystem;


use App\EventBusSystem\Event;
use App\EventBusSystem\EventBus;
use App\GameSystem\Factory;
use App\GraphicSystems\GraphicSystem;
use App\InputSystem\InputSystem;


class Game
{

    private Factory $factory;

    public function __construct()
    {
        $this->factory = new Factory();
    }

    public function run(): void
    {

        $eventBus = new EventBus();

        $systems = [
            $this->factory->createInputSystem(),
            $this->factory->createGameSystem(),
            $this->factory->createGraphicSystem()
        ];

        $eventBus->push(new Event('displayTutorial'));

        $i = 0;
        $countSystem = count($systems);
        while (count($eventBus->getEvents()) > 0) {
            $systems[$i]->processEvent($eventBus);
            $i++;
            if ($i === $countSystem) {
                $i = 0;
            }
        }
    }

}



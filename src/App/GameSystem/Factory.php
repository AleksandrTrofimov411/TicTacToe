<?php
declare(strict_types=1);

namespace App\GameSystem;


use App\GraphicSystems\GraphicSystem;
use App\InputSystem\InputSystem;
use App\GameSystem\GameManager;

class Factory
{

    private InputSystem $inputSystem;

    private GameManager $gameManager;

    private GraphicSystem $graphicSystem;

    public function createInputSystem(): InputSystem
    {
        return new InputSystem();
    }
    public function createGraphicSystem(): GraphicSystem
    {
        return new GraphicSystem();
    }
    public function createGameSystem(): GameManager
    {
        return new GameManager(3);
    }
}
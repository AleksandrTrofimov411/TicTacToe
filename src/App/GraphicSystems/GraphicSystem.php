<?php

declare(strict_types=1);

namespace App\GraphicSystems;

use AllowDynamicProperties;
use App\AbstractSystem;
use App\EventBusSystem\Event;
use App\EventBusSystem\EventBus;

class GraphicSystem extends AbstractSystem
{

    protected array $events = [
        'display' => 'displayMainScreen',
        'addPicture' => 'addPictureOnMainScreen',
        'displayErrorMessage' => 'addPictureOnMainScreen',
        'displayTutorial' => 'displayTutorial',
        'clearMainScreen' => 'createMainScreen'
    ];
    private array $mainScreen = [];

    private Painter $painter;

    public function __construct()
    {
        $this->painter = new Painter(50 ,25);
        $this->createMainScreen(null, null);
    }

    public function displayTutorial(Event|null $event, EventBus|null $eventBus): void
    {

        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, 'Welcome to TIC TAC TOE!', 11, 2);
        echo $this->toString();
        sleep(2);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen,"           X\n   -------------->\n | [   ][   ][   ]\nY| [   ][   ][   ]\n | [   ][   ][   ]\n v", 24, 6);
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "[   ][   ][   ]\n[   ][   ][   ]\n[   ][   ][   ]", 7, 8);
        echo $this->toString();
        sleep(2);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "[   ][ x ][   ]\n[   ][   ][   ]\n[   ][   ][   ]", 7, 12);
        echo $this->toString();
        sleep(2);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "[   ][ x ][ o ]\n[   ][   ][   ]\n[   ][   ][   ]", 7, 16);
        echo $this->toString();
        sleep(5);
        $this->createMainScreen(null, null);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, 'Welcome to TIC TAC TOE!', 11, 2);
        echo $this->toString();
        sleep(2);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen,"           X\n   -------------->\n | [   ][   ][   ]\nY| [   ][   ][   ]\n | [   ][   ][   ]\n v", 24, 6);
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "[   ][ x ][ o ]\n[   ][ x ][   ]\n[   ][   ][   ]", 7, 8);
        echo $this->toString();
        sleep(2);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "[   ][ x ][ o ]\n[   ][ x ][   ]\n[   ][   ][ o ]", 7, 12);
        echo $this->toString();
        sleep(2);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "[   ][ x ][ o ]\n[   ][ x ][   ]\n[   ][ x ][ o ]", 7, 16);
        echo $this->toString();
        sleep(5);
        popen('cls', 'w');
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, "CROSS VICTORY !", 11, 20);
        echo $this->toString();
        sleep(3);

        $eventBus->clearEvents();
        $eventBus->push(new Event('clearMainScreen'));

    }

    public function createMainScreen(Event|null $event, EventBus|null $eventBus): void
    {
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, $this->painter->getBorder(), 0, 0);

        if (isset($eventBus)) {
            $eventBus->clearEvents();
            if (isset($event->getData()['isVictory'])) {
                $victoryStone = $event->getData()['victoryStone'];
                $event = new Event('addPicture');
                $event->setData('picture', "$victoryStone WON !");
                $event->setData('x', 17);
                $event->setData('y', 10);
                $event->setData('isVictory', 0);
                $eventBus->push($event);
            } elseif (isset($event->getData()['isFieldFool'])) {
                $event = new Event('addPicture');
                $event->setData('isFieldFool', 0);
                $event->setData('picture', 'FIELD FOOL!');
                $event->setData('x', 17);
                $event->setData('y', 10);
                $eventBus->push($event);
            } else {
                $this->addHelpfulMessageOnMainScreen();
                $eventBus->push(new Event('addGamePictureOnMainScreen'));
            }

        }
    }

    public function addHelpfulMessageOnMainScreen(): void
    {
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, 'Welcome to TIC TAC TOE !', 11,2);
        $this->mainScreen = $this->painter->addPicture($this->mainScreen,"           X\n   -------------->\n | [   ][   ][   ]\nY| [   ][   ][   ]\n | [   ][   ][   ]\n v", 23, 6);
    }

    public function addPictureOnMainScreen(Event|null $event, EventBus $eventBus): void
    {
        $data = $event->getData();
        $x = $data['x'];
        $y = $data['y'];
        $picture = $data['picture'];
        $this->mainScreen = $this->painter->addPicture($this->mainScreen, $picture, $x, $y);
        $eventBus->clearEvents();
        if (isset($event->getData()['isVictory'])) {
            $event = new Event('display');
            $event->setData('isVictory', 0);
            $eventBus->push($event);
        } elseif (isset($event->getData()['isFieldFool'])) {
            $event = new Event('display');
            $event->setData('isFieldFool', 0);
            $eventBus->push($event);
        } elseif (isset($event->getData()['isError'])) {
            $event = new Event('display');
            $event->setData('isError', 0);
            $eventBus->push($event);
        } else {
            $event = new Event('display');
            $eventBus->push($event);
        }

    }

    public function toString(): string
    {
        $picture = '';
        foreach($this->mainScreen as $line) {
            $picture .= implode('', $line);
            $picture .= "\n";
        }
        return $picture;
    }

    public function displayMainScreen(Event|null $event, EventBus $eventBus): void
    {
        popen('cls', 'w');
        echo $this->toString();
        $eventBus->clearEvents();
        if (isset($event->getData()['isVictory']) || isset($event->getData()['isFieldFool'])) {
            $eventBus->clearEvents();
        } elseif (isset($event->getData()['isError'])) {
            sleep(3);
            $event = new Event('clearMainScreen');
            $eventBus->push($event);
        } else {
            $event = new Event('inputCoordinate');
            $eventBus->push($event);
        }
    }
}
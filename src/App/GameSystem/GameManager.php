<?php
declare(strict_types=1);

namespace App\GameSystem;

use App\AbstractSystem;
use App\EventBusSystem\Event;
use App\EventBusSystem\EventBus;
use Exception;
use App\GameSystem\Stone;

class GameManager extends AbstractSystem
{

    const

        ZERO_NUMBER = 2,

        CROSS_NUMBER = 1,

        CROSS_PICTURE = '[ x ]',

        ZERO_PICTURE = '[ o ]',

        EMPTY_CELL_PICTURE = '[   ]';

    private array $stoneName = [
        1 => 'CROSSES',
        2 => 'ZEROES'
        ];

    private int $stepCounter = 0;

    private array $storage;

    private int $fieldSize;

    protected array $events = [
        'updateGameState' => 'updateGameState',
        'addGamePictureOnMainScreen' => 'toString'
    ];

    public function __construct(int $fieldSize)
    {
        $this->fieldSize = $fieldSize;
    }

    public function createStone(int $stepCounter, int $x, int $y): Stone
    {
        $typeStone = $this->determineTypeStone($stepCounter);
        return new Stone($typeStone, $x, $y);

    }

    public function addStone(Stone $stone): void
    {
        $this->storage[] = $stone;
    }

    public function updateGameState(Event|null $event, EventBus $eventBus): void
    {
        $this->stepCounter++;
        $data = $event->getData();
        $stone = new Stone($this->determineTypeStone($this->stepCounter), $data['x'], $data['y']);
        try {
            $this->isCoordinateExists($stone);
            $this->thisCellIsFree($stone);
            $this->addStone($stone);
            $eventBus->clearEvents();
            $event = new Event('clearMainScreen');
            if ($this->isGameOver($this->stepCounter)) {
                $event->setData('victoryStone', $this->stoneName[$this->determineTypeStone($this->stepCounter)]);
                $event->setData('isVictory', 0);
                $eventBus->push($event);
            } elseif ($this->isFieldFool()) {
                $event->setData('isFieldFool', 0);
                $eventBus->push($event);
            } else {
                $eventBus->push($event);
            }

        } catch (Exception $e) {
            $this->stepCounter--;
            $eventBus->clearEvents();
            $event = new Event('displayErrorMessage'); // сообщене об ошибке на пропадает через время
            $event->setData('isError', 0);
            $event->setData('picture', $e->getMessage());
            $event->setData('x', 5);
            $event->setData('y', 20);
            $eventBus->push($event);
        }

    }

    public function cancelLastMove(): void
    {
        array_pop($this->storage);
    }

    /**
     * @throws Exception
     */
    public function isCoordinateExists(Stone $stone): void
    {

        if ($stone->getX() >= $this->fieldSize || $stone->getX() < 0 || $stone->getY() >= $this->fieldSize || $stone->getY() < 0) {
            throw new Exception('ERROR! Such a coordinate does not exist!');
        }

    }

    /**
     * @throws Exception
     */

    public function thisCellIsFree(Stone $stone): void
    {
        if (empty($this->storage)) {
            return ;
        }
        foreach ($this->storage as $item) {
            if ($item->getX() === $stone->getX() && $item->getY() === $stone->getY()) {
                throw new Exception('Error! Cell is busy!');
            }
        }
    }

    public function determineTypeStone(int $stepCounter): int
    {
        if ($stepCounter % 2 !== 0) {
            return self::CROSS_NUMBER;
        } else {
            return self::ZERO_NUMBER;
        }
    }

    public function toOneDimensionalCoordinates(int $x, int $y): int
    {
        return $x + $y * $this->fieldSize;
    }

    public function toTwoDimensionalCoordinates(int $coordinate): array
    {

        $x = $coordinate - ($this->fieldSize * (int)($coordinate / $this->fieldSize));
        if ($coordinate >= $this->fieldSize) {
            $y = ($coordinate - $x) / $this->fieldSize;
        } else {
            $y = 0;
        }
        return ['x' => $x, 'y' => $y];
    }

    public function createField(): array
    {
        $field = [];
        for ($i = 0; $i < pow($this->fieldSize, 2); $i++) {
            $field[$i] = 0;
        }
        return $field;
    }

    public function putStonesOnTheField(array $field): array
    {
        if (empty($this->storage)) {
            return $field;
        }
        foreach ($this->storage as $stone) {
            $x = $stone->getX();
            $y = $stone->getY();
            $coordinate = $this->toOneDimensionalCoordinates($x ,$y);
            $field[$coordinate] = $stone->getTypeStone();
        }
        return $field;
    }

    public function toString(Event|null $event, EventBus $eventBus): void
    {

        $field = $this->createField();
        $field = $this->putStonesOnTheField($field);
        $gamePicture = '';
        $stepCounter = 0;
        foreach ($field as $stone) {
            $gamePicture .= $this->getStonePicture($stone);
            $stepCounter++;
            if ($stepCounter === $this->fieldSize) {
                $gamePicture.= "\n";
                $stepCounter = 0;
            }

        }
        $eventBus->clearEvents();
        $event = new Event('addPicture');
        $event->setData('picture', $gamePicture);
        $event->setData('x', 5);
        $event->setData('y', 8);
        $eventBus->push($event);

    }

    public function getStonePicture(int $stone): string
    {
        if ($stone === self::CROSS_NUMBER) {
            return self::CROSS_PICTURE;
        } elseif ($stone === self::ZERO_NUMBER) {
            return self::ZERO_PICTURE;
        } else {
            return self::EMPTY_CELL_PICTURE;
        }

    }

    public function isGameOver(int $stepCounter): bool
    {

        $field = $this->createField();
        $field = $this->putStonesOnTheField($field);
        $typeStone = $this->determineTypeStone($stepCounter);
        $isVictory = $this->fieldSize;

//        var_dump($field);
//        sleep(5);
        // -----------------------------------------Горизонталь -

        for ($i = 0; $i < $this->fieldSize; $i++) {
            $stoneCounter = 0 ;
            for ($j = 0; $j < $this->fieldSize; $j++) {

                if ($field[$this->toOneDimensionalCoordinates($j, $i)] !== $typeStone) {
                    break ;
                }

                if ($field[$this->toOneDimensionalCoordinates($j, $i)] === $typeStone) {
                    $stoneCounter++;
                }
            }
            if ($stoneCounter === $isVictory) {
                return true;
            }
        }

        //-------------------------------------------Вертикаль |

        for ($i = 0; $i < $this->fieldSize; $i++) {
            $stoneCounter = 0 ;
            for ($j = 0; $j < $this->fieldSize; $j++) {

                if ($field[$this->toOneDimensionalCoordinates($i, $j)] !== $typeStone) {
                    break ;
                }
                if ($field[$this->toOneDimensionalCoordinates($i, $j)] === $typeStone) {
                    $stoneCounter++;
                }
                if ($stoneCounter === $isVictory) {
                    return true ;
                }
            }
        }

        //-------------------------------------------Диагональ \

        $stoneCounter = 0;
        for ($i = 0; $i < $this->fieldSize; $i++) {

            if ($field[$this->fieldSize * $i + $i] !== $typeStone) {
                break;
            }
            if ($field[$this->fieldSize * $i + $i] === $typeStone) {
                $stoneCounter++;
            }
            if ($stoneCounter === $isVictory) {
                return true;
            }
        }

        //-------------------------------------------Диагональ /

        $stoneCounter = 0 ;

        for ($i = 1; $i <= $this->fieldSize; $i++){

            if ($field[($this->fieldSize - 1) * $i] !== $typeStone) {
                break;
            }
            if ($field[($this->fieldSize - 1) * $i] === $typeStone) {
                $stoneCounter++;
            }
            if ($stoneCounter === $isVictory) {
                return true;
            }
        }

        return false ;

    }

    public function isFieldFool(): bool
    {
        return count($this->storage) === pow($this->fieldSize, 2);
    }

}
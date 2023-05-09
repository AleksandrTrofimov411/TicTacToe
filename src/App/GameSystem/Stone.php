<?php
declare(strict_types=1);


namespace App\GameSystem;;

class Stone
{

    private int $x;

    private int $y;

    private int $typeStone;

    public function __construct(int $typeStone, int $x, int $y)
    {
        $this->x = $x - 1;
        $this->y = $y - 1;
        $this->typeStone = $typeStone;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getTypeStone(): int
    {
        return $this->typeStone;
    }
}
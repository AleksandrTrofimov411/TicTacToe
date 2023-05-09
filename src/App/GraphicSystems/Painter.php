<?php
declare(strict_types=1);

namespace App\GraphicSystems;

use App\GraphicSystems\Border;
class Painter
{

    private Border $border;

    public function __construct(int $width, int $height)
    {
        $this->border = new Border();
        $this->border->createBorder($width, $height);
    }

    public function addPicture(array $mainScreen, string $picture, int $x, int $y): array
    {

        $picture = str_split($picture);
        $initialX = $x;

        for ($i = 0; $i < count($picture); $i++) {
            if ($picture[$i] === "\n") {
                $y++;
                $x = $initialX;
                continue;
            }

            $mainScreen = $this->addSymbol($mainScreen, $picture[$i], $x, $y);
            $x++;

        }

        return $mainScreen;

    }

    public function addSymbol(array $mainScreen, string $symbol, int $x, int $y): array
    {
        $mainScreen[$y][$x] = $symbol;
        return $mainScreen;
    }

    public function getBorder(): string
    {
        return $this->border->toString();
    }

    public function replaceStyleBorder(string $style): void
    {
        $this->border->setStyleBorder($style);
    }

}
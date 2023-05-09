<?php
declare(strict_types=1);

namespace App\GraphicSystems;
class Border
{

    private array $lines;

    private string $styleBorder = '*';

    public function createBorder(int $width, int $height): void
    {
        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {

                if ($i === 0 || $j === 0 || $j === $width - 1 || $i === $height - 1) {
                    $this->lines[$i][$j] = $this->styleBorder;
                    continue;
                } else {
                    $this->lines[$i][$j] = ' ';
                }
            }
        }
    }

    public function toString(): string
    {
        $border = '';

        foreach ($this->lines as $line) {
            $border .= implode('', $line);
            $border .= "\n";
        }
        return $border;
    }

    public function setStyleBorder(string $style): void
    {
        $this->styleBorder = $style;
    }


}
<?php

declare(strict_types=1);

namespace App\InputSystem;
class Keyboard
{
    public function input(string $message): string
    {
        return readline($message);
    }
}
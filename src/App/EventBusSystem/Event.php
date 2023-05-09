<?php
declare(strict_types=1);
namespace App\EventBusSystem;

class Event
{
    private string $type;

    private array $data = [];

    private int $usedChecker = 0;

    const
        EVENTS_USED = 1;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function setData(string $key, string|int $data): void
    {
        $this->data[$key] = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setUsedChecker(int $num): void
    {
        $this->usedChecker = $num;
    }

    public function getUsedChecker(): bool
    {
        if ($this->usedChecker === self::EVENTS_USED) {
            return true;
        } else {
            return false;
        }
    }
}
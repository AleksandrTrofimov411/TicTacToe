<?php 
declare(strict_types = 1);
use App\GameSystem\Game;
require_once __DIR__ . '/vendor/autoload.php';

$run = new Game();
$run->run();
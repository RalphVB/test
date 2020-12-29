<?php

use Statistic\Interfaces\ScoreDataIndexerInterface;
use Statistic\Models\Calculation;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

return [
    ScoreDataIndexerInterface::class => DI\create( Calculation::class ),
    Environment::class => function () {
        $loader = new FilesystemLoader( __DIR__ . "/../src/Statistic/Views");
        return new Environment($loader);
    }
];
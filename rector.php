<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Netwerkstatt\SilverstripeRector\Set\SilverstripeLevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app/_config.php',
        __DIR__ . '/app/src',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_85,
        SilverstripeLevelSetList::UP_TO_SS_6_2,
    ]);

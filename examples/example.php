<?php declare(strict_types=1);

namespace App;

use Effect\Effect;
use Effect\System;

require_once __DIR__ . '/../vendor/autoload.php';

class ReadDir implements Effect
{
    public string $dir;

    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }
}

class Log implements Effect
{
    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}

$handler = function (Effect $effect) {
    if ($effect instanceof ReadDir) {
        return scandir($effect->dir);
    }

    if ($effect instanceof Log) {
        echo $effect->message, PHP_EOL;
    }
};

$enumerate_files = (new System($handler))->affect(function (System $system) {
    return function (string $dir) use ($system) {
        $system->effect(new Log("Reading $dir"));

        $contents = $system->effect(new ReadDir($dir));

        $len = sizeof($contents);

        $system->effect(new Log("Found $len items"));

        return $contents;
    };
});

print_r($enumerate_files(__DIR__));


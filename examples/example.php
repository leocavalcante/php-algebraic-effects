<?php declare(strict_types=1);

namespace App;

use Effect\Affect;
use Effect\Effect;
use Effect\EffectHandler;

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

class ScanDir implements EffectHandler
{
    public function handle(Effect $effect)
    {
        if ($effect instanceof ReadDir) {
            return scandir($effect->dir);
        }
    }
}

class EchoLog implements EffectHandler
{
    public function handle(Effect $effect)
    {
        if ($effect instanceof Log) {
            echo $effect->message;
        }
    }
}

class EnumerateFiles extends Affect
{
    public function __invoke(string $dir): array
    {
        $this->perform(new Log("Reading $dir"));

        $contents = $this->perform(new ReadDir($dir));

        $len = sizeof($contents);

        $this->perform(new Log("Found $len items"));

        return $contents;
    }
}

$handlers = [new ScanDir(), new EchoLog()];
$enumerate_files = new EnumerateFiles($handlers);
print_r($enumerate_files(__DIR__));
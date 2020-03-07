<?php declare(strict_types=1);

namespace App;

use DirectoryIterator;
use Effect\Affects;
use Effect\Effect;
use Effect\Handler;

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

class ScanDir implements Handler
{
    /**
     * @param ReadDir|Effect $effect
     * @return array
     */
    public function __invoke(Effect $effect): array
    {
        if (($contents = scandir($effect->dir)) === false) {
            return [];
        }

        return $contents;
    }
}

class EchoLog implements Handler
{
    /**
     * @param Log|Effect $effect
     */
    public function __invoke(Effect $effect)
    {
        echo $effect->message, PHP_EOL;
    }
}

class EnumerateFiles extends Affects
{
    public function __invoke(string $dir): array
    {
        $this->affect(new Log("Reading $dir"));

        $contents = $this->affect(new ReadDir($dir));

        $len = sizeof($contents);

        $this->affect(new Log("Found $len items"));

        return $contents;
    }
}

$handlers = [
    ReadDir::class => new ScanDir(),
    Log::class => new EchoLog(),
];

$enumerate_files = new EnumerateFiles($handlers);

print_r($enumerate_files(__DIR__));

$enumerate_with_iterator = $enumerate_files->infect(ReadDir::class, new class implements Handler {
    /**
     * @param ReadDir|Effect $effect
     * @return array
     */
    public function __invoke(Effect $effect): array
    {
        return iterator_to_array(new DirectoryIterator($effect->dir));
    }
});

print_r($enumerate_with_iterator(__DIR__));
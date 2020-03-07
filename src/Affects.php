<?php declare(strict_types=1);

namespace Effect;

use RuntimeException;

class Affects
{
    /** @var array<class-string, EffectHandler> */
    private array $handlers;

    /**
     * @param array<class-string, EffectHandler> $handler
     */
    public function __construct(array $handler)
    {
        $this->handlers = $handler;
    }

    /**
     * @param string $effectClass
     * @psalm-param class-string $effectClass
     * @param Handler $handler
     * @return $this
     */
    public function infect(string $effectClass, Handler $handler): self
    {
        $this->handlers[$effectClass] = $handler;
        return $this;
    }

    protected function affect(Effect $effect)
    {
        /** @var class-string $class_name */
        $class_name = get_class($effect);
        /** @var Handler|null $handler */
        $handler = $this->handlers[$class_name] ?? null;

        if ($handler === null) {
            throw new RuntimeException("Unhandled $class_name effect");
        }

        return $handler($effect);
    }
}
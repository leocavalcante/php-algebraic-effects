<?php declare(strict_types=1);

namespace Effect;

use Closure;

class System
{
    /**
     * @var callable
     */
    private $handler;

    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    public function effect(Effect $effect)
    {
        return ($this->handler)($effect);
    }

    public function affect(callable $computation): Closure
    {
        return $computation($this);
    }
}
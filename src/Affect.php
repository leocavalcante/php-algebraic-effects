<?php declare(strict_types=1);

namespace Effect;

class Affect
{
    /** @var EffectHandler[] */
    private array $handler;

    /**
     * @param EffectHandler[] $handler
     */
    public function __construct(array $handler)
    {
        $this->handler = $handler;
    }

    public function perform(Effect $effect)
    {
        $result = null;

        foreach ($this->handler as $handler) {
            $result = $handler->handle($effect) ?? $result;
        }

        return $result;
    }
}
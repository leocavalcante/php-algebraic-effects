<?php declare(strict_types=1);

namespace Effect;

interface EffectHandler
{
    public function handle(Effect $effect);
}
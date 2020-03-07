<?php declare(strict_types=1);

namespace Effect;

interface Handler
{
    public function __invoke(Effect $effect);
}
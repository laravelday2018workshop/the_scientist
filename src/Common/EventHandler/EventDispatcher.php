<?php

declare(strict_types=1);

namespace Acme\Common\EventHandler;

interface EventDispatcher
{
    public function __invoke($event): void;
}

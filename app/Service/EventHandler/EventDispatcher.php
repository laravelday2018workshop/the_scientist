<?php

declare(strict_types=1);

namespace App\Service\EventHandler;

use Acme\Common\EventHandler\EventDispatcher as DomainEventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

final class EventDispatcher implements DomainEventDispatcher
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function __invoke($event): void
    {
        $this->dispatcher->dispatch($event);
    }
}

<?php

namespace STS\Tunneler\Events;

use STS\Tunneler\Jobs\CreateTunnel;

abstract class AbstractTunnelEvent
{
    public $job;
    public $port;
    public $address;

    public function __construct(CreateTunnel $job)
    {
        $this->job = $job;
        $this->port = config('tunneler.bind_address');
        $this->address = config('tunneler.bind_port');
    }
}

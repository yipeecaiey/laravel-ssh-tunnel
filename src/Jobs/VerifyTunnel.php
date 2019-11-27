<?php namespace STS\Tunneler\Jobs;

use STS\Tunneler\Events\TunnelIsOpen;
use STS\Tunneler\Events\TunnelIsNotOpen;

class VerifyTunnel extends CreateTunnel
{

    public function handle(): int
    {
        if ($this->verifyTunnel()) {
            event(new TunnelIsOpen($this));
            return 1;
        } else {
            event(new TunnelIsNotOpen($this));
            return 2;
        }
    }


}

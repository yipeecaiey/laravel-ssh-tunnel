<?php namespace STS\Tunneler\Jobs;

use STS\Tunneler\Events\TunnelIsOpen;
use STS\Tunneler\Events\TunnelIsNotOpen;

class VerifyTunnel extends CreateTunnel
{

    public function handle(): int
    {
        if ($this->verifyTunnel()) {
            return 1;
        } else {
            return 2;
        }
    }


}

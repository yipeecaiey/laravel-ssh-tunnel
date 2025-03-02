<?php namespace STS\Tunneler\Jobs;

use STS\Tunneler\Events\DroppingTunnel;
use STS\Tunneler\Events\TunnelIsNotOpen;
use STS\Tunneler\Events\TunnelIsOpen;

class DropTunnel extends CreateTunnel
{

    public function __construct() {

        parent::__construct();

        $this->dropCommand = sprintf( '%s aux | %s \'%s\' | %s \'{print $2}\' | %s kill',
            config('tunneler.ps_path'),
            config('tunneler.grep_path'),
            $this->sshCommand,
            config('tunneler.awk_path'),
            config('tunneler.xargs_path'),
        );
    }

    public function handle(): int
    {
        if (!$this->verifyTunnel()) {
            event(new TunnelIsNotOpen($this));
            return 1;
        }

        $this->dropTunnel();
        
        $tries = config('tunneler.tries');
        for ($i = 0; $i < $tries; $i++) {
            if (!$this->verifyTunnel()) {
                event(new TunnelIsNotOpen($this));
                return 2;
            }
            
            // Wait a bit until next iteration
            usleep(config('tunneler.wait'));
        }

        event(new TunnelIsOpen($this));
        throw new \ErrorException(sprintf("Could Not Drop SSH Tunnel with command:\n\t%s\nCheck your configuration.",
            $this->dropCommand));
    }

    public function dropTunnel() {

        event(new DroppingTunnel($this));

        $this->runCommand(sprintf('%s %s >> %s 2>&1 &',
            config('tunneler.nohup_path'),
            $this->sshCommand,
            config('tunneler.nohup_log')
        ));
        // Ensure we wait long enough for it to actually disconnect.
        usleep(config('tunneler.wait'));

    }


}

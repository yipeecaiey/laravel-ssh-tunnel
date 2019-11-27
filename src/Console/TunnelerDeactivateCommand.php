<?php namespace STS\Tunneler\Console;

use Illuminate\Console\Command;
use STS\Tunneler\Jobs\DropTunnel;

class TunnelerDeactivateCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tunneler:deactivate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates an SSH Tunnel';

    public function handle(){
        try {
            $result = dispatch_now(new DropTunnel());
        }catch (\ErrorException $e){
            $this->error($e->getMessage());
            return 1;
        }

        if ($result === 1 ){
            $this->info('The Tunnel is already Deactivated.');
            return 0;
        }

        if ($result === 2 ){
            $this->info('The Tunnel has been Deactivated.');
            return 0;
        }

        $this->warn('I have no idea how this happened. Let me know if you figure it out.');
        return 1;
    }
}
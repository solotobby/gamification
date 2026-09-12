<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AutoApprove7Days extends Command
{
    protected $signature = 'campaigns:auto-approve-7days';
    protected $description = '[DEPRECATED] Use campaigns:auto-approve instead';

    public function handle()
    {
        $this->warn('This command is deprecated. Auto-approval is now unified under `campaigns:auto-approve`.');
        $this->info('Forwarding to `campaigns:auto-approve`...');

        return Artisan::call('campaigns:auto-approve', [], $this->getOutput());
    }
}

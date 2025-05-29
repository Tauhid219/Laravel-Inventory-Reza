<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeCleanRun extends Command
{
    protected $signature = 'optimize:cleanrun';
    protected $description = 'Clear cache, logs, and optimize Laravel app';

    public function handle()
    {
        $this->info('🔄 Clearing Laravel caches...');
        $this->call('optimize:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        $this->info('🧹 Deleting log files...');
        $logFiles = File::glob(storage_path('logs/*.log'));
        foreach ($logFiles as $file) {
            File::delete($file);
        }

        $this->info('⚡ Rebuilding config, route, view, and event cache...');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('event:cache');

        $this->info('✅ Optimization complete!');
        return Command::SUCCESS;
    }
}

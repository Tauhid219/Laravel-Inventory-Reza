<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class TestCommand extends Command
{
    protected $signature = 'test
        {--filter= : Filter which tests to run}
        {--testsuite= : Filter which test suite to run}
        {--stop-on-failure : Stop after the first failing test}';

    protected $description = 'Run the PHPUnit test suite';

    public function handle(): int
    {
        $command = [
            PHP_OS_FAMILY === 'Windows'
                ? base_path('vendor/bin/phpunit.bat')
                : base_path('vendor/bin/phpunit'),
        ];

        if ($this->option('filter')) {
            $command[] = '--filter';
            $command[] = (string) $this->option('filter');
        }

        if ($this->option('testsuite')) {
            $command[] = '--testsuite';
            $command[] = (string) $this->option('testsuite');
        }

        if ($this->option('stop-on-failure')) {
            $command[] = '--stop-on-failure';
        }

        $process = new Process($command, base_path(), [
            'APP_ENV' => 'testing',
            'BCRYPT_ROUNDS' => '4',
            'CACHE_DRIVER' => 'array',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'MAIL_MAILER' => 'array',
            'QUEUE_CONNECTION' => 'sync',
            'SESSION_DRIVER' => 'array',
            'TELESCOPE_ENABLED' => 'false',
        ]);
        $process->setTimeout(null);

        return $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });
    }
}

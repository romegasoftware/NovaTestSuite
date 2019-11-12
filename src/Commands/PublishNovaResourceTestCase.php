<?php

namespace Romegadigital\NovaTestSuite\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishNovaResourceTestCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova:test-case';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes NovaResourceTestCase class';

    protected $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Publishing NovaResourceTestCase');

        if (! $this->files->isDirectory($directory = base_path('tests/Feature/Nova'))) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $this->files->copy(
            __DIR__ . '/stubs/NovaResourceTestCase.stub',
            $directory . '/NovaResourceTestCase.php'
        );

        $this->info('Published NovaResourceTest');
    }
}

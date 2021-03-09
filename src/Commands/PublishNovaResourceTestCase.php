<?php

namespace RomegaSoftware\NovaTestSuite\Commands;

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

    protected Filesystem $files;

    protected string $novaTestDirectory;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->novaTestDirectory = base_path('tests/Feature/Nova');

        if ($this->file->exists($this->novaTestDirectory . '/NovaResourceTestCase.php')) {
            $this->setHidden(true);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Publishing NovaResourceTestCase');

        if (!$this->files->isDirectory($this->novaTestDirectory)) {
            $this->files->makeDirectory($this->novaTestDirectory, 0755, true);
        }

        $this->files->copy(
            __DIR__ . '/stubs/NovaResourceTestCase.stub',
            $this->novaTestDirectory . '/NovaResourceTestCase.php'
        );

        $this->info('Published NovaResourceTest');
    }
}

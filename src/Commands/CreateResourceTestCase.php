<?php

namespace RomegaSoftware\NovaTestSuite\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class CreateResourceTestCase extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nova:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new nova Resource test case';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource Test';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        if (!$this->files->exists($this->laravel->basePath('tests/Feature/Nova/NovaResourceTestCase.php'))) {
            $this->callSilent('nova:test-case');
        }

        parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/ResourceTest.stub';
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return str_replace(
            '{{ modelName }}',
            str_replace('Test', '', $this->getNameInput()),
            $stub
        );
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = parent::getNameInput();

        return Str::endsWith($name, 'Test')
            ? $name
            : $name . 'Test';
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel->basePath('tests') . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'Tests';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Feature\Nova';
    }
}

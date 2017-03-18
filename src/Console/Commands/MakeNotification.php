<?php

namespace Packages\CmsDev\Console\Commands;

use Packages\CmsDev\Support\Abstracts\Generator;

class MakeNotification extends Generator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:make:notification {package} {name} {--markdown}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new evenotificationnt class for package';

    protected $type = 'Notification';

    public function fire()
    {
        if (parent::fire() === false) {
            return;
        }

        if ($this->option('markdown')) {
            $this->writeMarkdownTemplate();
        }
    }

    protected function writeMarkdownTemplate()
    {
        $path = package_path($this->getPackageInput(), 'resources/views/'.str_replace('.', '/', $this->option('markdown'))).'.blade.php';

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }

        $this->files->put($path, file_get_contents(base_path('vendor\laravel\framework\src\Illuminate\Foundation\Console\stubs\markdown.stub')));
    }

    protected function buildClass($name)
    {
        $class = parent::buildClass($name);

        if ($this->option('markdown')) {
            $class = str_replace('DummyView', $this->option('markdown'), $class);
        }

        return $class;
    }

    protected function getStub()
    {
        return $this->option('markdown')
            ? base_path('vendor\laravel\framework\src\Illuminate\Foundation\Console\stubs\markdown-notification.stub')
            : base_path('vendor\laravel\framework\src\Illuminate\Foundation\Console\stubs\notification.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Notifications';
    }
}

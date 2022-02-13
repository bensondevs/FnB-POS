<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateServiceClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {service : Name of service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service class.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Acquire the service name
        $insertedName = $this->argument('service');

        // Get the real service name
        // Separate it if there is a path added to the name
        $explode = explode('/', $insertedName);
            
        // Get the service name and remove it from the explode array
        $serviceName = $explode[count($explode) - 1]; 
        unset($explode[count($explode) - 1]);

        // If there is still something in explode array
        // Then there must be a extended path added
        $extPath = count($explode) ?
            '/' . implode('/', $explode) . '/' : '';

        // Create or check base path of the service
        $basePath = app_path('Services');
        if (! file_exists($basePath)) {
            shell_exec('mkdir ' . $basePath);
        }

        // Check the "will-be-created" service is not exist in the first place
        // If yes, just return error and don't continue
        $createdFolder = concat_paths([
            $basePath, 
            $extPath
        ]);
        $createdPath = concat_paths([
            $createdFolder,
            $serviceName
        ]) . '.php';
        if (file_exists($createdPath)) {
            return $this->error('Service class already exists!');
        }

        // Get the service class template
        $templatePath = resource_path('stubs/Service.stub');
        $templateContent = file_get_contents($templatePath);
        
        // Make the content of the service class
        $serviceClassContent = str_replace([
            '{{serviceName}}', '{{extendedPath}}'
        ], [
            $serviceName, str_replace('/', '\\', substr($extPath, 0, -1))
        ], $templateContent);

        // Create service class file in the target folder
        if (! file_exists($createdFolder)) {
            mkdir($createdFolder, 0755);
        }

        // Put templated content to the file that has been created
        if (! file_put_contents($createdPath, $serviceClassContent)) {
            return $this->error('Failed to create service class file to folder.');
        }

        return $this->info('Service class has been created successfully!');;
    }
}

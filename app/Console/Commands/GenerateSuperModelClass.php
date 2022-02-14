<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSuperModelClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:supermodel {supermodel : Name of supermodel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate super model class.';

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
        // Acquire the supermodel name
        $insertedName = $this->argument('supermodel');

        // Get the real supermodel name
        // Separate it if there is a path added to the name
        $explode = explode('/', $insertedName);
            
        // Get the supermodel name and remove it from the explode array
        $supermodelName = $explode[count($explode) - 1]; 
        unset($explode[count($explode) - 1]);

        // If there is still something in explode array
        // Then there must be a extended path added
        $extPath = count($explode) ?
            '/' . implode('/', $explode) . '/' : '';

        // Create or check base path of the supermodel
        $basePath = app_path('Models');
        if (! file_exists($basePath)) {
            shell_exec('mkdir ' . $basePath);
        }

        // Check the "will-be-created" supermodel is not exist in the first place
        // If yes, just return error and don't continue
        $createdFolder = concat_paths([
            $basePath, 
            $extPath
        ]);
        $createdPath = concat_paths([
            $createdFolder,
            $supermodelName
        ]) . '.php';
        if (file_exists($createdPath)) {
            return $this->error('Model class already exists!');
        }

        // Get the supermodel class template
        $templatePath = resource_path('stubs/SuperModel.stub');
        $templateContent = file_get_contents($templatePath);
        
        // Make the content of the supermodel class
        $supermodelClassContent = str_replace([
            '{{modelName}}', 
            '{{extendedPath}}', 
            '{{modelNamePluralLowerCase}}'
        ], [
            $supermodelName, 
            str_replace('/', '\\', substr($extPath, 0, -1)),
            str_snake_case(str_to_plural($supermodelName))
        ], $templateContent);

        // Create supermodel class file in the target folder
        if (! file_exists($createdFolder)) {
            mkdir($createdFolder, 0755);
        }

        // Put templated content to the file that has been created
        if (! file_put_contents($createdPath, $supermodelClassContent)) {
            return $this->error('Failed to create supermodel class file to folder.');
        }

        // Generate observer class
        artisan_call('make:observer ' . $supermodelName . 'Observer --model=' . $supermodelName);

        // Generate factory class
        artisan_call('make:factory ' . $supermodelName . 'Factory');

        return $this->info('Model class has been created successfully!');;
    }
}

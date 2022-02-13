<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRepositoryClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {repository : Name of repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate repository class.';

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
        // Acquire the repository name
        $insertedName = $this->argument('repository');

        // Get the real repository name
        // Separate it if there is a path added to the name
        $explode = explode('/', $insertedName);
            
        // Get the repository name and remove it from the explode array
        $repositoryName = $explode[count($explode) - 1]; 
        unset($explode[count($explode) - 1]);

        // If there is still something in explode array
        // Then there must be a extended path added
        $extPath = count($explode) ?
            '/' . implode('/', $explode) . '/' : '';

        // Create or check base path of the repository
        $basePath = app_path('Repositories');
        if (! file_exists($basePath)) {
            shell_exec('mkdir ' . $basePath);
        }

        // Check the "will-be-created" repository is not exist in the first place
        // If yes, just return error and don't continue
        $createdFolder = concat_paths([
            $basePath, 
            $extPath
        ]);
        $createdPath = concat_paths([
            $createdFolder,
            $repositoryName
        ]) . '.php';
        if (file_exists($createdPath)) {
            return $this->error('Repository class already exists!');
        }

        // Get the repository class template
        $templatePath = resource_path('stubs/Repository.stub');
        $templateContent = file_get_contents($templatePath);
        
        // Make the content of the repository class
        $repositoryClassContent = str_replace([
            '{{repositoryName}}', '{{extendedPath}}'
        ], [
            $repositoryName, str_replace('/', '\\', substr($extPath, 0, -1))
        ], $templateContent);

        // Create repository class file in the target folder
        if (! file_exists($createdFolder)) {
            mkdir($createdFolder, 0755);
        }

        // Put templated content to the file that has been created
        if (! file_put_contents($createdPath, $repositoryClassContent)) {
            return $this->error('Failed to create repository class file to folder.');
        }

        return $this->info('Repository class has been created successfully!');;
    }
}

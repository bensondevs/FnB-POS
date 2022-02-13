<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateContractClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:contract {contract : Name of contract}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate contract class.';

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
        // Acquire the contract name
        $insertedName = $this->argument('contract');

        // Get the real contract name
        // Separate it if there is a path added to the name
        $explode = explode('/', $insertedName);
            
        // Get the contract name and remove it from the explode array
        $contractName = $explode[count($explode) - 1]; 
        unset($explode[count($explode) - 1]);

        // If there is still something in explode array
        // Then there must be a extended path added
        $extPath = count($explode) ?
            '/' . implode('/', $explode) . '/' : '';

        // Create or check base path of the contract
        $basePath = app_path('Contracts');
        if (! file_exists($basePath)) {
            shell_exec('mkdir ' . $basePath);
        }

        // Check the "will-be-created" contract is not exist in the first place
        // If yes, just return error and don't continue
        $createdFolder = concat_paths([
            $basePath, 
            $extPath
        ]);
        $createdPath = concat_paths([
            $createdFolder,
            $contractName
        ]) . '.php';
        if (file_exists($createdPath)) {
            return $this->error('Contract class already exists!');
        }

        // Get the contract class template
        $templatePath = resource_path('stubs/Contract.stub');
        $templateContent = file_get_contents($templatePath);
        
        // Make the content of the contract class
        $contractClassContent = str_replace([
            '{{contractName}}', '{{extendedPath}}'
        ], [
            $contractName, str_replace('/', '\\', substr($extPath, 0, -1))
        ], $templateContent);

        // Create contract class file in the target folder
        if (! file_exists($createdFolder)) {
            mkdir($createdFolder, 0755);
        }

        // Put templated content to the file that has been created
        if (! file_put_contents($createdPath, $contractClassContent)) {
            return $this->error('Failed to create contract class file to folder.');
        }

        return $this->info('Contract class has been created successfully!');;
    }
}
